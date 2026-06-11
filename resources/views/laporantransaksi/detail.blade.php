@extends('layouts.app')

@section('title', 'Detail Laporan Transaksi - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-receipt', 'title' => 'Detail Transaksi', 'subtitle' => \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <button onclick="window.open('/laporan/transaksi/cetak/{{ $tanggal }}','_blank')" class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> Cetak PDF</button>
            <a href="{{ route('laporantransaksi.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @php $totalPendapatan = 0; $totalItemTerjual = 0;
        foreach($itemsSummary as $item) { $totalPendapatan += $item->total_revenue; $totalItemTerjual += $item->total_quantity; }
    @endphp

    <div class="summary-strip">
        <div class="strip-item">
            <i class="bi bi-cash-coin"></i>
            <div><span class="strip-label">Tunai (Cash)</span><span class="strip-value green">Rp {{ number_format($totalCash, 0, ',', '.') }}</span></div>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-item">
            <i class="bi bi-qr-code"></i>
            <div><span class="strip-label">QRIS (QR)</span><span class="strip-value blue">Rp {{ number_format($totalQris, 0, ',', '.') }}</span></div>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-item">
            <i class="bi bi-bag-check"></i>
            <div><span class="strip-label">Item Terjual</span><span class="strip-value">{{ $totalItemTerjual }} item</span></div>
        </div>
        <div class="strip-divider"></div>
        <div class="strip-item highlight">
            <i class="bi bi-wallet2"></i>
            <div><span class="strip-label">Total Pendapatan</span><span class="strip-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span></div>
        </div>
    </div>

    <div class="data-card mb-4">
        <div class="data-card-header"><h6><i class="bi bi-box-seam"></i> Ringkasan Item Terjual</h6></div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Item</th>
                        <th class="text-end">Total Terjual</th>
                        <th class="text-end">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itemsSummary as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-end"><span class="badge-soft aman">{{ $item->total_quantity }} Pcs</span></td>
                        <td class="text-end text-success fw-bold">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-row">Tidak ada item yang terjual pada tanggal ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="data-card mb-4">
        <div class="data-card-header"><h6><i class="bi bi-credit-card"></i> Daftar Transaksi & Metode Pembayaran</h6></div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Waktu</th>
                        <th>Metode</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>#{{ $trx->id }}</td>
                        <td>{{ $trx->created_at->format('H:i') }}</td>
                        <td>
                            @if($trx->payment_method === 'qris')
                                <span class="badge-soft" style="background:#e0f2fe;color:#075985;"><i class="bi bi-qr-code"></i> QRIS</span>
                            @else
                                <span class="badge-soft" style="background:#dcfce7;color:#166534;"><i class="bi bi-cash"></i> Tunai</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="empty-row">Tidak ada transaksi pada tanggal ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .summary-strip { display:flex; align-items:center; background:#fff; border-radius:12px; padding:18px 24px; box-shadow:0 1px 4px rgba(15,23,42,.08); margin-bottom:24px; gap:20px; flex-wrap:wrap; border-left:4px solid var(--brand-primary); }
        .strip-item { display:flex; align-items:center; gap:12px; }
        .strip-item i { font-size:22px; color:var(--text-muted); }
        .strip-item.highlight { background:var(--brand-gradient); padding:10px 18px; border-radius:8px; margin-left:auto; }
        .strip-item.highlight i, .strip-item.highlight .strip-label, .strip-item.highlight .strip-value { color:#fff !important; }
        .strip-label { display:block; font-size:12px; color:var(--text-muted); }
        .strip-value { display:block; font-size:16px; font-weight:700; color:var(--text-primary); }
        .strip-value.green { color:var(--success); }
        .strip-value.blue { color:var(--brand-primary); }
        .strip-divider { width:1px; height:36px; background:var(--border-soft); }
        @media(max-width:768px){ .summary-strip{ flex-direction:column; align-items:flex-start; } .strip-divider{ width:100%; height:1px; } .strip-item.highlight{ margin-left:0; width:100%; } }
    </style>
@endsection
