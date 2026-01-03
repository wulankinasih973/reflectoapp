<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Statistik Reflecto</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #000; padding: 6px; text-align:left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Statistik Jurnal Reflecto</h2>
    <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr><th>Statistik</th><th>Nilai</th></tr>
        </thead>
        <tbody>
            <tr><td>Total Pengguna</td><td>{{ $totalUsers }}</td></tr>
            <tr><td>Total Jurnal</td><td>{{ $totalJournals }}</td></tr>
            <tr><td>Rata-Rata Mood</td><td>{{ $avgMood }}</td></tr>
            <tr><td>Rata-Rata Kecemasan</td><td>{{ $avgAnxiety }}</td></tr>
            <tr><td>Rata-Rata Stres</td><td>{{ $avgStress }}</td></tr>
        </tbody>
    </table>
</body>
</html>