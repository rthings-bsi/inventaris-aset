<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAudit;
use App\Models\AssetAuditItem;
use App\Models\AuditCriteriaGroup;
use App\Models\Category;
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

        if ($request->filled('audit_type')) {
            $query->where('audit_type', $request->audit_type);
        }

        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        $audits = $query->paginate(10)->withQueryString();
        return view('audits.index', compact('audits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'audit_date' => 'required|date',
            'audit_type' => 'required|in:full,sample,spot',
            'frequency' => 'nullable|in:weekly,monthly,quarterly,yearly',
            'selected_assets' => 'nullable|array',
            'selected_assets.*' => 'exists:assets,id_assets',
        ]);

        $audit = AssetAudit::create([
            'title' => $request->title,
            'description' => $request->description,
            'audit_type' => $request->audit_type,
            'frequency' => $request->frequency,
            'audit_date' => $request->audit_date,
            'status' => 'open',
            'next_audit_date' => $this->calculateNextAuditDate($request->frequency, $request->audit_date),
            'selected_assets' => $request->selected_assets,
            'created_by' => auth()->id(),
        ]);

        // Auto-populate audit items if assets selected or full audit
        if ($request->audit_type === 'full' || !empty($request->selected_assets)) {
            $this->populateAuditItems($audit, $request->selected_assets);
        }

        return redirect()->route('audits.index')->with('success', 'Sesi audit berhasil dimulai.');
    }

    public function show(AssetAudit $audit)
    {
        $items = $audit->items()->with('asset')->latest()->get();

        // Get criteria groups for grading
        $criteriaGroups = AuditCriteriaGroup::with('items')->get();

        // Get asset status breakdown
        $stats = [
            'total' => $items->count(),
            'pending' => $items->where('checklist_status', 'pending')->count(),
            'scanned' => $items->whereIn('checklist_status', ['scanned', 'checked', 'verified'])->count(),
            'grade_a' => $items->where('condition_grade', 'A')->count(),
            'grade_b' => $items->where('condition_grade', 'B')->count(),
            'grade_c' => $items->where('condition_grade', 'C')->count(),
            'grade_d' => $items->where('condition_grade', 'D')->count(),
            'grade_e' => $items->where('condition_grade', 'E')->count(),
        ];

        return view('audits.scan', compact('audit', 'items', 'criteriaGroups', 'stats'));
    }

    /**
     * Show checklist table view for this audit session
     */
    public function checklist(AssetAudit $audit)
    {
        $items = $audit->items()->with('asset.category', 'asset.location')->get();

        // Group assets by category
        $groupedAssets = $items->groupBy(function ($item) {
            return $item->asset ? ($item->asset->category->category_name ?? 'Tanpa Kategori') : 'Aset Tidak Terdaftar';
        });

        $criteriaGroups = AuditCriteriaGroup::with('items')->get();
        $categories = Category::all();

        return view('audits.checklist', compact('audit', 'items', 'groupedAssets', 'criteriaGroups', 'categories'));
    }

    /**
     * Bulk update checklist items from the table view
     */
    public function checklistUpdate(Request $request, AssetAudit $audit)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:asset_audit_items,id_asset_audit_items',
            'items.*.checked' => 'boolean',
            'items.*.criteria' => 'nullable|array',
            'items.*.notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $audit) {
            foreach ($request->items as $data) {
                $item = AssetAuditItem::where('id_asset_audits', $audit->id_asset_audits)
                    ->where('id_asset_audit_items', $data['id'])
                    ->first();
                if (!$item) continue;

                $updateData = [];

                if (isset($data['checked']) && $data['checked']) {
                    $updateData['checklist_status'] = 'checked';
                    $updateData['scanned_at'] = $item->scanned_at ?? now();
                    if (!$item->scanned_code) {
                        $updateData['scanned_code'] = $item->asset->asset_code ?? 'CHECKLIST-'.$item->id_asset_audit_items;
                    }
                    if (!$item->id_assets && $item->asset) {
                        $updateData['id_assets'] = $item->asset->id_assets;
                    }
                }

                if (isset($data['criteria'])) {
                    $updateData['criteria_data'] = $data['criteria'];
                    // Calculate condition score and grade from criteria
                    $grading = $this->calculateGradeFromCriteria($data['criteria'], $item->asset);
                    $updateData['condition_score'] = $grading['score'];
                    $updateData['condition_grade'] = $grading['grade'];
                }

                if (isset($data['notes'])) {
                    $updateData['notes'] = $data['notes'];
                }

                if (!empty($updateData)) {
                    $item->update($updateData);

                    // Update asset condition if graded
                    if (isset($updateData['condition_grade']) && $item->asset) {
                        $conditionMap = [
                            'A' => 'active',
                            'B' => 'active',
                            'C' => 'maintenance',
                            'D' => 'maintenance',
                            'E' => 'broken',
                        ];
                        $newStatus = $conditionMap[$updateData['condition_grade']] ?? 'active';
                        if ($item->asset->status !== 'disposed') {
                            $item->asset->update(['condition' => $updateData['condition_grade'], 'status' => $newStatus]);
                        }
                    }
                }
            }
        });

        return redirect()->route('audits.checklist', $audit)
            ->with('success', 'Data checklist audit berhasil diperbarui.');
    }

    /**
     * Grade a single audit item via AJAX
     */
    public function gradeItem(Request $request, AssetAudit $audit, AssetAuditItem $item)
    {
        if ($item->id_asset_audits !== $audit->id_asset_audits) {
            return response()->json(['message' => 'Item tidak sesuai dengan sesi audit.'], 403);
        }

        $request->validate([
            'criteria' => 'required|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        $grading = $this->calculateGradeFromCriteria($request->criteria, $item->asset);

        $item->update([
            'criteria_data' => $request->criteria,
            'condition_score' => $grading['score'],
            'condition_grade' => $grading['grade'],
            'checklist_status' => 'verified',
            'notes' => $request->notes ?? $item->notes,
            'scanned_at' => $item->scanned_at ?? now(),
        ]);

        // Update asset condition
        if ($item->asset) {
            $conditionMap = [
                'A' => 'active', 'B' => 'active', 'C' => 'maintenance',
                'D' => 'maintenance', 'E' => 'broken',
            ];
            $newStatus = $conditionMap[$grading['grade']] ?? 'active';
            if ($item->asset->status !== 'disposed') {
                $item->asset->update(['condition' => $grading['grade'], 'status' => $newStatus]);
            }
        }

        return response()->json([
            'success' => true,
            'grade' => $grading['grade'],
            'score' => $grading['score'],
            'label' => $item->condition_grade_badge,
            'message' => "Aset {$item->asset?->asset_name} telah dinilai dengan grade {$grading['grade']}.",
        ]);
    }

    public function scan(Request $request, AssetAudit $audit)
    {
        if ($audit->status !== 'open') {
            return response()->json(['message' => 'Audit ini sudah ditutup.'], 403);
        }

        $code = trim(urldecode($request->code));

        // Handle URL-based barcodes
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

        $finalCode = $asset ? $asset->asset_code : $code;

        // Check if already scanned
        $existing = AssetAuditItem::where('id_asset_audits', $audit->id_asset_audits)
            ->where(function ($query) use ($asset, $finalCode) {
                $query->where('scanned_code', $finalCode);
                if ($asset) {
                    $query->orWhere('id_assets', $asset->id_assets);
                }
            })
            ->first();

        if ($existing) {
            // Already exists - if pending, mark as scanned
            if ($existing->checklist_status === 'pending') {
                $existing->update([
                    'checklist_status' => 'scanned',
                    'scanned_at' => now(),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => $asset ? "Aset {$asset->asset_name} berhasil dipindai (dari antrean)." : "Kode {$code} dipindai.",
                    'item' => [
                        'code' => $finalCode,
                        'name' => $asset ? $asset->asset_name : 'Tidak Terdaftar',
                        'status' => 'scanned',
                        'time' => now()->format('H:i:s'),
                    ]
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Aset ini sudah dipindai sebelumnya.'
            ]);
        }

        // Load criteria groups for this asset's category
        $criteriaGroups = collect();
        if ($asset) {
            $criteriaGroups = AuditCriteriaGroup::with('items')
                ->where('category_type', $asset->category?->category_name)
                ->orWhereNull('category_type')
                ->get();
        }

        $item = AssetAuditItem::create([
            'id_asset_audits' => $audit->id_asset_audits,
            'id_assets' => $asset ? $asset->id_assets : null,
            'scanned_code' => $finalCode,
            'status' => $asset ? 'present' : 'unexpected',
            'checklist_status' => $asset ? 'scanned' : 'checked',
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $asset ? "Aset {$asset->asset_name} berhasil dipindai." : "Kode {$code} dipindai (Aset tidak terdaftar).",
            'item' => [
                'code' => $finalCode,
                'name' => $asset ? $asset->asset_name : 'Tidak Terdaftar',
                'status' => $item->checklist_status,
                'time' => $item->scanned_at->format('H:i:s'),
                'id' => $item->id_asset_audit_items,
                'needs_grading' => $asset && $criteriaGroups->isNotEmpty(),
                'criteria_groups' => $criteriaGroups->isEmpty() ? null : $criteriaGroups,
            ]
        ]);
    }

    public function complete(AssetAudit $audit)
    {
        // Auto-grade any pending items that have criteria data
        $pendingItems = $audit->items()->where('checklist_status', 'pending')->get();
        foreach ($pendingItems as $item) {
            if ($item->criteria_data) {
                $grading = $this->calculateGradeFromCriteria($item->criteria_data, $item->asset);
                $item->update([
                    'condition_score' => $grading['score'],
                    'condition_grade' => $grading['grade'],
                    'checklist_status' => 'checked',
                ]);
            } else {
                // Mark as unchecked
                $item->update(['checklist_status' => 'checked']);
            }
        }

        // Calculate next audit date if frequency is set
        $nextDate = null;
        if ($audit->frequency) {
            $nextDate = $this->calculateNextAuditDate($audit->frequency, $audit->audit_date->format('Y-m-d'));
        }

        $audit->update([
            'status' => 'completed',
            'next_audit_date' => $nextDate,
        ]);

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

        // Grade breakdown
        $gradeBreakdown = $audit->items()
            ->select('condition_grade', DB::raw('count(*) as total'))
            ->whereNotNull('condition_grade')
            ->groupBy('condition_grade')
            ->pluck('total', 'condition_grade')
            ->toArray();

        return view('audits.report', compact('audit', 'missingAssets', 'unexpectedItems', 'foundItems', 'gradeBreakdown'));
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
        $gradeBreakdown = $audit->items()
            ->select('condition_grade', DB::raw('count(*) as total'))
            ->whereNotNull('condition_grade')
            ->groupBy('condition_grade')
            ->pluck('total', 'condition_grade')
            ->toArray();

        $pdf = Pdf::loadView('audits.report_pdf', compact('audit', 'missingAssets', 'unexpectedItems', 'foundItems', 'gradeBreakdown'));
        return $pdf->download("Audit_{$audit->title}.pdf");
    }

    public function destroy(AssetAudit $audit)
    {
        $audit->delete();
        return redirect()->route('audits.index')->with('success', 'Sesi audit berhasil dihapus.');
    }

    // ─── Private Methods ─────────────────────────────────────────────

    private function calculateGradeFromCriteria(?array $criteria, ?Asset $asset): array
    {
        if (empty($criteria)) {
            return ['score' => 0, 'grade' => 'C'];
        }

        $totalWeight = 0;
        $earnedScore = 0;

        foreach ($criteria as $criterionId => $value) {
            // Find the criteria item to get weight
            $criteriaItem = \App\Models\AuditCriteriaItem::find($criterionId);
            if (!$criteriaItem) continue;

            $weight = $criteriaItem->weight;
            $totalWeight += $weight;

            // Value could be boolean (checked/unchecked) or integer (1-5 scale)
            if (is_bool($value)) {
                $earnedScore += $value ? $weight : 0;
            } elseif (is_numeric($value)) {
                // Scale 1-5
                $earnedScore += ($value / 5) * $weight;
            }
        }

        $percentage = $totalWeight > 0 ? ($earnedScore / $totalWeight) * 100 : 0;

        // Determine grade
        $grade = match(true) {
            $percentage >= 90 => 'A',
            $percentage >= 75 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 40 => 'D',
            default => 'E',
        };

        return [
            'score' => round($percentage, 2),
            'grade' => $grade,
        ];
    }

    private function populateAuditItems(AssetAudit $audit, ?array $selectedAssetIds = null): void
    {
        $query = Asset::query();

        if (!empty($selectedAssetIds)) {
            $query->whereIn('id_assets', $selectedAssetIds);
        }

        $assets = $query->get();

        $items = [];
        foreach ($assets as $asset) {
            $items[] = [
                'id_asset_audits' => $audit->id_asset_audits,
                'id_assets' => $asset->id_assets,
                'scanned_code' => $asset->asset_code,
                'status' => 'pending',
                'checklist_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($items)) {
            AssetAuditItem::insert($items);
        }
    }

    private function calculateNextAuditDate(?string $frequency, string $fromDate): ?string
    {
        if (!$frequency) return null;

        $date = \Carbon\Carbon::parse($fromDate);

        return match($frequency) {
            'weekly' => $date->addWeek()->format('Y-m-d'),
            'monthly' => $date->addMonth()->format('Y-m-d'),
            'quarterly' => $date->addMonths(3)->format('Y-m-d'),
            'yearly' => $date->addYear()->format('Y-m-d'),
            default => null,
        };
    }
}
