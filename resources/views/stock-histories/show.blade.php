@extends('layouts.app')

@section('title', 'Detail Barang Keluar - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-clock-history', 'title' => 'Detail Riwayat Stok', 'subtitle' => 'Detail penggunaan bahan'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('barangkeluar.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-row"><span class="key">Bahan</span><span class="val">{{ $stockHistory->ingredient->nama ?? '-' }}</span></div>
        <div class="detail-row"><span class="key">Jumlah Keluar</span><span class="val">{{ $stockHistory->jumlah }} {{ $stockHistory->unit }}</span></div>
        <div class="detail-row"><span class="key">Tanggal</span><span class="val">{{ \Carbon\Carbon::parse($stockHistory->tanggal)->format('d M Y H:i') }}</span></div>
        <div class="detail-row"><span class="key">Keterangan</span><span class="val">{{ $stockHistory->keterangan ?? '-' }}</span></div>
        <div class="detail-row"><span class="key">Stok Sebelumnya</span><span class="val">{{ $stockHistory->stock->jumlah + $stockHistory->jumlah }} {{ $stockHistory->unit }}</span></div>
        <div class="detail-row"><span class="key">Stok Setelah</span><span class="val">{{ $stockHistory->stock->jumlah }} {{ $stockHistory->unit }}</span></div>
        <div class="detail-row"><span class="key">Dibuat</span><span class="val">{{ $stockHistory->created_at->format('d/m/Y H:i:s') }}</span></div>
    </div>
@endsection
