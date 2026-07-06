<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetRepair;
use Illuminate\Http\Request;

class AssetRepairController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetRepair::with('asset', 'creator')->latest();

        if ($request->filled('asset_id')) {
            $query->where('id_assets', $request->asset_id);
        }

        if ($request->filled('repair_type')) {
            $query->where('repair_type', $request->repair_type);
        }

        $repairs = $query->paginate(15)->withQueryString();
        $totalCost = (clone $query)->sum('cost');
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
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        AssetRepair::create([
            'id_assets' => $request->id_assets,
            'repair_date' => $request->repair_date,
            'description' => $request->description,
            'repair_type' => $request->repair_type,
            'cost' => $request->cost,
            'vendor' => $request->vendor,
            'notes' => $request->notes,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data perbaikan berhasil dicatat.');
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
            'status' => 'required|in:pending,in_progress,completed',
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
