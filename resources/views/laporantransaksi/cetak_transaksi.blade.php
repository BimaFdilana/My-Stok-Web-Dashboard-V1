<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin-top: 5px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        table th {
            background: #0B3BB6;
            color: white;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
        }

        .summary p {
            margin: 5px 0;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Transaksi Harian</h2>
        <p>
            Tanggal:
            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
        </p>
    </div>

    @php
        $totalPendapatan = 0;
        $totalItemTerjual = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th>Nama Item</th>
                <th width="20%">Jumlah Terjual</th>
                <th width="25%">Pendapatan</th>
            </tr>
        </thead>

        <tbody>

            @forelse($itemsSummary as $index => $item)

                <tr>
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $item->name }}
                    </td>

                    <td class="text-center">
                        {{ $item->total_quantity }}
                    </td>

                    <td class="text-right">
                        Rp{{ number_format($item->total_revenue, 0, ',', '.') }}
                    </td>
                </tr>

                @php
                    $totalPendapatan += $item->total_revenue;
                    $totalItemTerjual += $item->total_quantity;
                @endphp

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        Tidak ada transaksi
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

    <div class="summary">
        <p>
            Total Item Terjual :
            <strong>{{ $totalItemTerjual }}</strong>
        </p>

        <p class="total">
            Total Pendapatan :
            Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
        </p>
    </div>

</body>

</html>