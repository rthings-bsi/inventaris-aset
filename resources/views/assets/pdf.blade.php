<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
        th { background-color: #4f46e5; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .subtitle { font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Inventaris Aset</div>
        <div class="subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }} | Total Aset: {{ $assets->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Aset</th>
                <th>Category</th>
                <th>Location</th>
                <th>Condition</th>
                <th>Status</th>
                <th>Nilai Perolehan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->asset_code }}</td>
                <td>{{ $asset->asset_name }}</td>
                <td>{{ $asset->category ? $asset->category->category_name : '-' }}</td>
                <td>{{ $asset->location ? $asset->location->location_name : '-' }}</td>
                <td>{{ $asset->condition }}</td>
                <td>{{ ucfirst($asset->status) }}</td>
                <td>Rp {{ number_format($asset->acquisition_cost, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data aset.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
