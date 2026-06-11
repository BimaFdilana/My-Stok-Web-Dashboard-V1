@extends('layouts.app')

@section('title', 'Laporan Barang Masuk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-file-earmark-arrow-down', 'title' => 'Laporan Barang Masuk', 'subtitle' => 'Rekap penerimaan bahan per tanggal'])

    <div class="report-grid">
        @forelse($laporanPerTanggal as $laporan)
        <div class="report-card">
            <div class="report-date"><i class="bi bi-calendar3"></i> {{ Carbon\Carbon::parse($laporan['tanggal'])->translatedFormat('d F Y') }}</div>
            <div class="report-body">
                <span class="text-muted" style="font-size:13px;">Total Item: <strong>{{ $laporan['total_items'] }}</strong></span>
                <div class="report-actions">
                    <a href="{{ route('laporanmasuk.detail', ['tanggal' => $laporan['tanggal']]) }}" class="btn btn-view btn-sm"><i class="bi bi-eye"></i> Detail</a>
                    <button onclick="window.open('/laporan/barangmasuk/cetak/{{ $laporan['tanggal'] }}','_blank')" class="btn btn-edit btn-sm"><i class="bi bi-printer"></i> Cetak</button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-row" style="grid-column:1/-1;">Tidak ada laporan barang masuk</div>
        @endforelse
    </div>

    <style>
        .report-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        @media(max-width:992px){ .report-grid{ grid-template-columns:repeat(2,1fr); } }
        @media(max-width:576px){ .report-grid{ grid-template-columns:1fr; } }
        .report-card { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(15,23,42,.06); overflow:hidden; transition:transform .2s; }
        .report-card:hover { transform:translateY(-2px); }
        .report-date { background:var(--brand-gradient); color:#fff; padding:12px 16px; font-weight:600; font-size:14px; }
        .report-body { padding:16px; }
        .report-actions { display:flex; gap:8px; margin-top:12px; }
    </style>
@endsection
