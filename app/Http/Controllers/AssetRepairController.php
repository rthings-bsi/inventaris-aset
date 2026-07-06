<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\AssetRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetRepairController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetRepair::with(['asset', 'creator', 'approver', 'loan'])->latest();

        if ($request->filled('asset_id')) {
            $query->where('id_assets', $request->asset_id);
        }

        if ($request->filled('repair_type')) {
            $query->where('repair_type', $request->repair_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $repairs = $query->paginate(15)->withQueryString();
        $totalCost = (clone $query)->where('status', '!=', 'reported')->sum('cost');
        $assets = Asset::all();

        return view('repairs.index', compact('repairs', 'totalCost', 'assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_assets' => 'required|exists:assets,id_assets',
            'repair_date' => 'required|date',
            'description' => 'required|string|max:1000',
            'repair_type' => 'required|in:maintenance,damage,warranty',
            'cost' => 'required|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        AssetRepair::create([
            'id_assets' => $request->id_assets,
            'id_asset_loans' => $request->id_asset_loans,
            'repair_date' => $request->repair_date,
            'description' => $request->description,
            'repair_type' => $request->repair_type,
            'cost' => $request->cost,
            'vendor' => $request->vendor,
            'notes' => $request->notes,
            'status' => AssetRepair::STATUS_REPORTED,
            'created_by' => auth()->id(),
        ]);

        // Update asset status immediately when damage is reported
        $asset = Asset::find($request->id_assets);
        if ($asset) {
            $statusMap = [
                'damage' => 'broken',
                'maintenance' => 'maintenance',
                'warranty' => 'maintenance',
            ];
            $conditionMap = [
                'damage' => 'D',
                'maintenance' => 'C',
                'warranty' => 'C',
            ];
            $asset->update([
                'status' => $statusMap[$request->repair_type] ?? 'maintenance',
                'condition' => $conditionMap[$request->repair_type] ?? 'C',
            ]);
        }

        return redirect()->back()->with('success', 'Laporan perbaikan berhasil dibuat. Status aset telah diperbarui.');
    }

    /**
     * Mulai perbaikan (reported → in_progress)
     */
    public function start(AssetRepair $repair)
    {
        if (!$repair->canTransitionTo(AssetRepair::STATUS_IN_PROGRESS)) {
            return back()->with('error', 'Status tidak sesuai untuk memulai perbaikan.');
        }

        $repair->update(['status' => AssetRepair::STATUS_IN_PROGRESS]);

        // Update asset status
        $repair->asset->update(['status' => 'maintenance']);

        return back()->with('success', 'Perbaikan dimulai. Status aset: Maintenance.');
    }

    /**
     * Selesaikan perbaikan (in_progress → completed)
     */
    public function complete(Request $request, AssetRepair $repair)
    {
        if (!$repair->canTransitionTo(AssetRepair::STATUS_COMPLETED)) {
            return back()->with('error', 'Status tidak sesuai untuk menyelesaikan perbaikan.');
        }

        $request->validate([
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $repair->update([
            'status' => AssetRepair::STATUS_COMPLETED,
            'cost' => $request->cost ?? $repair->cost,
            'notes' => $request->notes ?? $repair->notes,
        ]);

        return back()->with('success', 'Perbaikan selesai. Menunggu persetujuan QC/Approval.');
    }

    /**
     * Approve hasil perbaikan (completed → approved) — oleh Supervisor/Admin
     */
    public function approve(Request $request, AssetRepair $repair)
    {
        if (!$repair->canTransitionTo(AssetRepair::STATUS_APPROVED)) {
            return back()->with('error', 'Perbaikan belum selesai atau sudah di-approve.');
        }

        $request->validate([
            'new_condition_grade' => 'nullable|in:A,B,C,D,E',
        ]);

        $repair->update([
            'status' => AssetRepair::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'new_condition_grade' => $request->new_condition_grade ?? $repair->suggested_grade,
        ]);

        return back()->with('success', 'Perbaikan disetujui. Siap untuk serah terima.');
    }

    /**
     * Serah terima asset (approved → handed_over) — oleh Admin
     */
    public function handover(Request $request, AssetRepair $repair)
    {
        if (!$repair->canTransitionTo(AssetRepair::STATUS_HANDED_OVER)) {
            return back()->with('error', 'Perbaikan belum di-approve atau sudah diserahterimakan.');
        }

        $request->validate([
            'new_condition_grade' => 'required|in:A,B,C,D,E',
            'handover_notes' => 'nullable|string|max:1000',
        ]);

        $newGrade = $request->new_condition_grade;

        $repair->update([
            'status' => AssetRepair::STATUS_HANDED_OVER,
            'handover_by' => auth()->id(),
            'handover_date' => now(),
            'new_condition_grade' => $newGrade,
            'handover_notes' => $request->handover_notes,
        ]);

        // Update asset condition & status
        $conditionMap = [
            'A' => 'active', 'B' => 'active', 'C' => 'maintenance',
            'D' => 'maintenance', 'E' => 'broken',
        ];
        $newStatus = $conditionMap[$newGrade] ?? 'active';

        $repair->asset->update([
            'condition' => $newGrade,
            'status' => $newStatus,
        ]);

        // If asset was borrowed for this repair loan, return it
        if ($repair->loan && $repair->loan->status === 'borrowed') {
            $repair->loan->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);
            $repair->asset->update(['id_users' => null]);
        }

        return back()->with('success', "Serah terima selesai! Grade aset: {$newGrade} — Status: {$newStatus}.");
    }

    public function update(Request $request, AssetRepair $repair)
    {
        $request->validate([
            'repair_date' => 'required|date',
            'description' => 'required|string|max:1000',
            'repair_type' => 'required|in:maintenance,damage,warranty',
            'cost' => 'required|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:reported,in_progress,completed,approved,handed_over',
        ]);

        $repair->update($request->only([
            'repair_date', 'description', 'repair_type', 'cost', 'vendor', 'notes', 'status'
        ]));

        return redirect()->back()->with('success', 'Data perbaikan berhasil diperbarui.');
    }

    public function destroy(AssetRepair $repair)
    {
        $repair->delete();
        return redirect()->back()->with('success', 'Data perbaikan berhasil dihapus.');
    }
}
