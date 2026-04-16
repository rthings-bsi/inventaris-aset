<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Audit - {{ $audit->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #4F46E5;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .meta {
            margin-bottom: 30px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 5px 0;
            font-size: 13px;
        }
        .summary {
            margin-bottom: 40px;
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #eee;
            border-radius: 10px;
        }
        .summary-item .label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            color: #999;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 4px solid #4F46E5;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th {
            background-color: #f8fafc;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            color: #64748b;
        }
        table.data-table td {
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-red { background-color: #fee2e2; color: #b91c1c; }
        .badge-green { background-color: #d1fae5; color: #047857; }
        .badge-amber { background-color: #fef3c7; color: #b45309; }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AUDIT ASET</h1>
        <p>Sistem Manajemen Inventaris AsetKu Enterprise</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="20%"><strong>Judul Audit</strong></td>
                <td>: {{ $audit->title }}</td>
                <td width="20%"><strong>Tanggal Audit</strong></td>
                <td>: {{ $audit->audit_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Status Sesi</strong></td>
                <td>: {{ ucfirst($audit->status) }}</td>
                <td><strong>Auditor</strong></td>
                <td>: {{ $audit->creator->name }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Sesuai (Found)</div>
            <div class="value" style="color: #059669;">{{ $foundItems->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Hilang (Missing)</div>
            <div class="value" style="color: #dc2626;">{{ $missingAssets->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Surplus (Unexpected)</div>
            <div class="value" style="color: #d97706;">{{ $unexpectedItems->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Stock</div>
            <div class="value">{{ $foundItems->count() + $missingAssets->count() }}</div>
        </div>
    </div>

    <div class="section-title" style="border-color: #dc2626; color: #dc2626;">Aset Hilang / Tidak Ditemukan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Status di Sistem</th>
                <th>Lokasi Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($missingAssets as $asset)
            <tr>
                <td><strong>{{ $asset->asset_code }}</strong></td>
                <td>{{ $asset->asset_name }}</td>
                <td>{{ $asset->status }}</td>
                <td>{{ $asset->location ? $asset->location->location_name : '-' }}</td>
            </tr>
            @endforeach
            @if($missingAssets->isEmpty())
            <tr>
                <td colspan="4" style="text-align: center; color: #059669;">Semua aset tercatat berhasil ditemukan di lapangan.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title" style="border-color: #d97706; color: #d97706;">Aset Tidak Terduga / Surplus</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Dipindai</th>
                <th>Status Audit</th>
                <th>Waktu Pindai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unexpectedItems as $item)
            <tr>
                <td><strong>{{ $item->scanned_code }}</strong></td>
                <td><span class="badge badge-amber">KODE TIDAK TERDAFTAR</span></td>
                <td>{{ $item->scanned_at->format('H:i:s') }}</td>
            </tr>
            @endforeach
            @if($unexpectedItems->isEmpty())
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada aset asing ditemukan.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title" style="border-color: #059669; color: #059669;">Aset Terverifikasi (Ditemukan)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Waktu Pindai</th>
                <th>Status Lapangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($foundItems as $item)
            <tr>
                <td><strong>{{ $item->asset->asset_code }}</strong></td>
                <td>{{ $item->asset->asset_name }}</td>
                <td>{{ $item->scanned_at->format('H:i:s') }}</td>
                <td><span class="badge badge-green">DITEMUKAN</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem AsetKu Enterprise pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
