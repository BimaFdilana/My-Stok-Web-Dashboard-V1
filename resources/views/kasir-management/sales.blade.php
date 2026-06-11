@extends('layouts.app')

@section('title', 'Riwayat Penjualan Kasir - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-receipt', 'title' => 'Riwayat Penjualan', 'subtitle' => 'Kasir: ' . $kasir->name])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('kasir-management.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="data-card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Metode</th>
                        <th>Jml Item</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                        <td>#{{ $trx->id }}</td>
                        <td>{{ $trx->created_at->format('d M Y') }}</td>
                        <td>{{ $trx->created_at->format('H:i') }}</td>
                        <td>
                            @if($trx->payment_method === 'qris')
                                <span class="badge-soft" style="background:#e0f2fe;color:#075985;"><i class="bi bi-qr-code"></i> QRIS</span>
                            @else
                                <span class="badge-soft" style="background:#dcfce7;color:#166534;"><i class="bi bi-cash"></i> Tunai</span>
                            @endif
                        </td>
                        <td>{{ $trx->details->sum('quantity') }}</td>
                        <td class="fw-bold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="empty-row">Belum ada transaksi dari kasir ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($transactions->hasPages())
    <div class="mt-3">{{ $transactions->links() }}</div>
    @endif
@endsection
