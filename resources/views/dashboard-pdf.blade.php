@php
function formatHour($jam) {
    if ($jam < 1) return round($jam * 60) . ' menit';
    return number_format($jam, 2) . ' jam';
}
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Statistik Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 0; }
        .kpi-card { display: inline-block; width: 23%; margin: 1%; padding: 10px; border: 1px solid #eee; border-radius: 8px; background: #f9f9f9; text-align: center; }
        .kpi-title { font-size: 13px; color: #888; }
        .kpi-value { font-size: 22px; font-weight: bold; margin: 5px 0; }
        .section-title { margin-top: 30px; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Laporan Statistik Dashboard</h2>
    <p>Periode: {{ date('Y') }}</p>
    <div>
        <div class="kpi-card">
            <div class="kpi-title">Approval Rate</div>
            <div class="kpi-value">{{ $kpi['approval_rate'] }}%</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Rata-rata Waktu Approval</div>
            <div class="kpi-value">{{ formatHour($kpi['avg_approval_time']) }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Total Approval</div>
            <div class="kpi-value">{{ $kpi['total_approval'] }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Total Divisi</div>
            <div class="kpi-value">{{ $kpi['total_division'] }}</div>
        </div>
    </div>
    <div class="section-title">Statistik Dokumen per Bulan</div>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chartData as $item)
            <tr>
                <td>{{ DateTime::createFromFormat('!m', $item->month)->format('F') }}</td>
                <td>{{ $item->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="section-title">Statistik Dokumen per Divisi</div>
    <table>
        <thead>
            <tr>
                <th>Divisi</th>
                <th>Jumlah Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chartDivision as $item)
            <tr>
                <td>{{ $item->division ? $item->division->name : '-' }}</td>
                <td>{{ $item->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="section-title">Rata-rata Waktu Approval per Bulan</div>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Rata-rata Waktu Approval</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chartApprovalTime as $item)
            <tr>
                <td>{{ DateTime::createFromFormat('!m', $item->month)->format('F') }}</td>
                <td>{{ $item->avg_time ? formatHour($item->avg_time/3600) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 