@extends('layouts.app')

@section('title', 'Detail Produk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-box-seam', 'title' => 'Detail Produk', 'subtitle' => $item->nama ?? ''])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('produk.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if($item)
    <div class="detail-card full">
        <div class="row">
            <div class="col-md-4 mb-3">
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="detail-img" style="max-width:100%;">
                @else
                    <div class="empty-row">Gambar tidak tersedia</div>
                @endif
            </div>
            <div class="col-md-8">
                <h3 style="margin:0 0 16px; font-weight:700;">{{ $item->nama }}</h3>
                <div class="detail-row"><span class="key">Kode</span><span class="val">{{ $item->kode }}</span></div>
                <div class="detail-row"><span class="key">Harga</span><span class="val">Rp {{ number_format($item->harga, 0, ',', '.') }}</span></div>
                <div class="detail-row"><span class="key">Kategori</span><span class="val">{{ $item->category->nama ?? $item->kategori_id }}</span></div>
                <h6 class="mt-4" style="font-weight:600;">Daftar Bahan</h6>
                <ul class="list-group">
                    @foreach($item->ingredients as $ingredient)
                    <li class="list-group-item d-flex justify-content-between">
                        {{ $ingredient->nama }}
                        <span class="badge bg-primary">{{ $ingredient->pivot->jumlah }} {{ $ingredient->pivot->satuan }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @else
        <div class="empty-row">Produk tidak ditemukan.</div>
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection
