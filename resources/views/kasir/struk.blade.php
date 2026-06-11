<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
        }

        .total {
            margin-top: 8px;
        }

        .payment-info {
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
        }

        h2 {
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        p {
            margin: 4px 0;
        }

        .btn {
            padding: 6px 12px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-success {
            background-color: #198754;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>BeansPoint</h2>
        <p>{{ date('d/m/Y H:i:s') }}</p>
        <p>No. Transaksi: {{ $transaction->id }}</p>
    </div>

    <div class="divider"></div>

    <div class="items">
        @foreach ($transaction->details as $detail)
            <div class="item">
                <span>{{ strtolower($detail->item->nama) }} x {{ $detail->quantity }}</span>
                <span>Rp {{ number_format($detail->total_price, 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="total">
        <div class="item">
            <span>Total:</span>
            <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment-info">
        <div class="item">
            <span>Metode Pembayaran:</span>
            <span>{{ strtoupper($paymentInfo['payment_method']) }}</span>
        </div>
        @if ($paymentInfo['payment_method'] === 'cash')
            <div class="item">
                <span>Tunai:</span>
                <span>Rp {{ number_format($paymentInfo['payment_amount'], 0, ',', '.') }}</span>
            </div>
            <div class="item">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($paymentInfo['change_amount'], 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Silahkan datang kembali</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-success">Cetak Struk</button>
        <a href="{{ route('transactions.index') }}">
            <button type="button" class="btn btn-secondary">Kembali</button>
        </a>
    </div>
</body>

</html>
