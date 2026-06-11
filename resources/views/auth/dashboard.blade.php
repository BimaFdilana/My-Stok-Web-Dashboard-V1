@extends('layouts.app')

@section('title', 'Dashboard - MyStock')

@section('content')
    @include('components.appbar', [
        'color' => 'blue',
        'icon' => 'bi-speedometer2',
        'title' => 'Dashboard',
        'subtitle' => 'Selamat datang, ' . $user->name . ' • ' . ucfirst($user->role ?? 'kasir'),
    ])

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-box"></i></div>
            <div class="stat-info">
                <p class="label">Total Barang</p>
                <div class="value">{{ number_format($totalBarang) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-arrow-down-square"></i></div>
            <div class="stat-info">
                <p class="label">Stok Masuk</p>
                <div class="value">{{ number_format($totalBarangMasuk) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-arrow-up-square"></i></div>
            <div class="stat-info">
                <p class="label">Stok Keluar</p>
                <div class="value">{{ number_format($totalBarangKeluar) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-receipt"></i></div>
            <div class="stat-info">
                <p class="label">Transaksi Hari Ini</p>
                <div class="value">{{ number_format($totalTransaksiHariIni) }}</div>
            </div>
        </div>
    </div>

    @if(($user->role ?? 'kasir') === 'admin')
    <div class="payment-grid">
        <div class="payment-card">
            <div class="stat-icon green"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-info">
                <p class="label">Pemasukan Tunai</p>
                <div class="value text-success">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="payment-card">
            <div class="stat-icon blue"><i class="bi bi-qr-code"></i></div>
            <div class="stat-info">
                <p class="label">Pemasukan QRIS</p>
                <div class="value text-primary">Rp {{ number_format($totalQris, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="payment-card gradient">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2); color:#fff;"><i class="bi bi-wallet2"></i></div>
            <div class="stat-info">
                <p class="label">Total Pendapatan Hari Ini</p>
                <div class="value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($stokKritis->isNotEmpty())
    <div class="alert-soft">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><strong>{{ $stokKritis->count() }} bahan</strong> memiliki stok kritis (di bawah 10). Segera lakukan restock.</span>
    </div>
    @endif

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-list-ul"></i> Daftar Stok</h6>
            @if(($user->role ?? 'kasir') === 'admin')
            <a href="{{ route('stocks.index') }}" class="btn-outline-primary-soft">Lihat Semua</a>
            @endif
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Satuan</th>
                        <th class="text-end">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        @php
                            $jumlah = $stock->jumlah;
                            $badge = $jumlah > 100 ? 'aman' : ($jumlah >= 10 ? 'medium' : 'kritis');
                        @endphp
                        <tr>
                            <td>{{ $stock->ingredient->kode ?? '-' }}</td>
                            <td>{{ $stock->ingredient->nama ?? '-' }}</td>
                            <td>{{ $stock->category->nama ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($stock->tanggal)->format('d M Y') }}</td>
                            <td>{{ $stock->satuan }}</td>
                            <td class="text-end"><span class="badge-soft {{ $badge }}">{{ $jumlah }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row">Belum ada data stok</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
