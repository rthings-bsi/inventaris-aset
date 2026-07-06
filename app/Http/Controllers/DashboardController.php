<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAudit;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_aset' => Asset::count(),
            'aset_aktif' => Asset::where('status', 'active')->count(),
            'aset_maintenance' => Asset::where('status', 'maintenance')->count(),
            'aset_rusak' => Asset::where('status', 'broken')->count(),
            'total_nilai' => Asset::sum('acquisition_cost')
        ];

        // Analytics Data for Chart.js
        $categoriesStat = Category::withCount('assets')->get();
        $category_labels = $categoriesStat->pluck('category_name');
        $category_data = $categoriesStat->pluck('assets_count')->toArray();
        
        $conditionStats = Asset::select('condition', DB::raw('count(*) as count'))
                               ->groupBy('condition')
                               ->get();
        $condition_labels = $conditionStats->pluck('condition');
        $condition_data = $conditionStats->pluck('count')->toArray();

        $chart_data = [
            'category' => [
                'labels' => $category_labels->toArray(), 
                'data' => $category_data
            ],
            'condition' => [
                'labels' => $condition_labels->toArray(), 
                'data' => $condition_data
            ]
        ];

        $recent_assets = Asset::latest()->take(5)->get();

        // ─── Audit Statistics ─────────────────────────────────
        $auditStats = [
            'total_audits' => AssetAudit::count(),
            'completed_audits' => AssetAudit::where('status', 'completed')->count(),
            'open_audits' => AssetAudit::where('status', 'open')->count(),
            'monthly_audits' => AssetAudit::whereMonth('audit_date', now()->month)
                ->whereYear('audit_date', now()->year)
                ->count(),
            'weekly_audits' => AssetAudit::whereBetween('audit_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'next_audit' => AssetAudit::where('status', 'open')
                ->whereNotNull('next_audit_date')
                ->orderBy('next_audit_date')
                ->first(),
            'upcoming_audits' => AssetAudit::where('status', 'open')
                ->where('audit_date', '>=', now())
                ->orWhere(function($q) {
                    $q->whereNotNull('next_audit_date')
                      ->where('next_audit_date', '>=', now());
                })
                ->orderBy('audit_date')
                ->take(3)
                ->get(),
        ];

        // Audit grade distribution (from completed audits)
        $gradeDistribution = Asset::select('condition', DB::raw('count(*) as count'))
            ->whereIn('condition', ['A', 'B', 'C', 'D', 'E'])
            ->groupBy('condition')
            ->orderBy('condition')
            ->get();
        $auditStats['grade_labels'] = $gradeDistribution->pluck('condition')->toArray();
        $auditStats['grade_data'] = $gradeDistribution->pluck('count')->toArray();

        // Audit history last 6 months
        $auditHistory = AssetAudit::select(
                DB::raw("strftime('%Y-%m', audit_date) as month"),
                DB::raw('count(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
            )
            ->where('audit_date', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("strftime('%Y-%m', audit_date)"))
            ->orderBy('month')
            ->get();
        $auditStats['history_months'] = $auditHistory->pluck('month')->toArray();
        $auditStats['history_total'] = $auditHistory->pluck('total')->toArray();
        $auditStats['history_completed'] = $auditHistory->pluck('completed')->toArray();
        
        return view('dashboard', compact('stats', 'recent_assets', 'chart_data', 'auditStats'));
    }
}
