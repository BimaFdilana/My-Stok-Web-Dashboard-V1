<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Barang Keluar</title>
    <style>
        /* Simplify the styles */
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 12px;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Barang Keluar</h2>
        <p>Tanggal: {{ Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th>Stock Terpakai</th>
                <th>Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockHistories as $index => $history)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $history->ingredient->nama ?? '-' }}</td>
                    <td>{{ $history->stock->category->nama ?? '-' }}</td>
                    <td>{{ $history->jumlah }} {{ $history->unit }}</td>
                    <td>{{ $history->tanggal ? $history->tanggal->format('H:i:s') : '-' }}</td>
                    <td>{{ $history->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right; font-size: 12px;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 