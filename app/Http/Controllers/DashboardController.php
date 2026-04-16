<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard with statistics
     */
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
        
        return view('dashboard', compact('stats', 'recent_assets', 'chart_data'));
    }
}
