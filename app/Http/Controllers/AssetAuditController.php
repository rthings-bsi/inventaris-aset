<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAudit;
use App\Models\AssetAuditItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetAuditExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AssetAuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetAudit::with('creator')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereAny(['title', 'description'], 'like', "%{$search}%");
        }

        $audits = $query->paginate(10)->withQueryString();
        return view('audits.index', compact('audits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'audit_date' => 'required|date',
        ]);

        AssetAudit::create([
            'title' => $request->title,
            'description' => $request->description,
            'audit_date' => $request->audit_date,
            'status' => 'open',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('audits.index')->with('success', 'Sesi audit berhasil dimulai.');
    }

    public function show(AssetAudit $audit)
    {
        $items = $audit->items()->with('asset')->latest()->get();
        return view('audits.scan', compact('audit', 'items'));
    }

    public function scan(Request $request, AssetAudit $audit)
    {
        if ($audit->status !== 'open') {
            return response()->json(['message' => 'Audit ini sudah ditutup.'], 403);
        }

        $code = trim(urldecode($request->code));

        // Handle URL-based barcodes (e.g. http://127.0.0.1:8000/assets/1)
        if (filter_var($code, FILTER_VALIDATE_URL) || strpos($code, 'http') === 0) {
            $path = parse_url($code, PHP_URL_PATH);
            if ($path) {
                $segments = explode('/', trim($path, '/'));
                if (!empty($segments)) {
                    $code = end($segments);
                }
            }
        }

        // Try to find the asset by code OR ID
        $asset = Asset::where('asset_code', $code)
            ->orWhere('id_assets', $code)
            ->first();

        // Use the actual asset_code if found, otherwise use the scanned code
        $finalCode = $asset ? $asset->asset_code : $code;

        // Check if already scanned in this session (by code or by asset_id)
        $existing = AssetAuditItem::where('id_asset_audits', $audit->id_asset_audits)
            ->where(function ($query) use ($asset, $finalCode) {
                $query->where('scanned_code', $finalCode);
                if ($asset) {
                    $query->orWhere('id_assets', $asset->id_assets);
                }
            })
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Aset ini sudah dipindai sebelumnya.'
            ]);
        }

        $item = AssetAuditItem::create([
            'id_asset_audits' => $audit->id_asset_audits,
            'id_assets' => $asset ? $asset->id_assets : null,
            'scanned_code' => $finalCode,
            'status' => $asset ? 'present' : 'unexpected',
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $asset ? "Aset {$asset->asset_name} berhasil dipindai." : "Kode {$code} dipindai (Aset tidak terdaftar).",
            'item' => [
                'code' => $finalCode,
                'name' => $asset ? $asset->asset_name : 'Tidak Terdaftar',
                'status' => $item->status,
                'time' => $item->scanned_at->format('H:i:s'),
            ]
        ]);
    }

    public function complete(AssetAudit $audit)
    {
        $audit->update(['status' => 'completed']);
        return redirect()->route('audits.index')->with('success', 'Audit berhasil diselesaikan.');
    }

    public function report(AssetAudit $audit)
    {
        $scannedItems = $audit->items()->pluck('id_assets')->filter()->toArray();
        
        // Items in system but not scanned
        $missingAssets = Asset::whereNotIn('id_assets', $scannedItems)->get();
        
        // Scanned but not in system
        $unexpectedItems = $audit->items()->whereNull('id_assets')->get();
        
        // Scanned and in system
        $foundItems = $audit->items()->whereNotNull('id_assets')->with('asset')->get();

        return view('audits.report', compact('audit', 'missingAssets', 'unexpectedItems', 'foundItems'));
    }

    public function exportExcel(AssetAudit $audit)
    {
        return Excel::download(new AssetAuditExport($audit), "Audit_{$audit->title}.xlsx");
    }

    public function exportPdf(AssetAudit $audit)
    {
        $scannedItems = $audit->items()->pluck('id_assets')->filter()->toArray();
        $missingAssets = Asset::whereNotIn('id_assets', $scannedItems)->get();
        $unexpectedItems = $audit->items()->whereNull('id_assets')->get();
        $foundItems = $audit->items()->whereNotNull('id_assets')->with('asset')->get();

        $pdf = Pdf::loadView('audits.report_pdf', compact('audit', 'missingAssets', 'unexpectedItems', 'foundItems'));
        return $pdf->download("Audit_{$audit->title}.pdf");
    }

    public function destroy(AssetAudit $audit)
    {
        $audit->delete();
        return redirect()->route('audits.index')->with('success', 'Sesi audit berhasil dihapus.');
    }
}
