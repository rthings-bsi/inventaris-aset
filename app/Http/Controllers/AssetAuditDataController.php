<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AssetAuditDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AssetAuditDataController extends Controller
{
    public function download(Request $request)
    {
        $period = $request->query('period', 'monthly'); // default monthly

        $validPeriods = ['weekly', 'monthly', 'yearly'];
        if (!in_array($period, $validPeriods)) {
            $period = 'monthly';
        }

        $fileName = 'Data_Audit_Semua_' . ucfirst($period) . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AssetAuditDataExport($period), $fileName);
    }
}
