@extends('layouts.app')

@section('title', 'Stok - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-archive', 'title' => 'Daftar Stok', 'subtitle' => 'Monitoring stok bahan baku'])

    <div class="page-header">
        <div class="title-group"></div>
    </div>

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="data-card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Satuan</th>
                        <th class="text-end">Stok Awal</th>
                        <th class="text-end">Keluar</th>
                        <th class="text-end">Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        @php $badge = $stock->jumlah > 100 ? 'aman' : ($stock->jumlah >= 10 ? 'medium' : 'kritis'); @endphp
                        <tr>
                            <td>{{ $stock->ingredient->kode ?? '-' }}</td>
                            <td>{{ $stock->ingredient->nama ?? '-' }}</td>
                            <td>{{ $stock->category->nama ?? '-' }}</td>
                            <td>{{ $stock->tanggal->format('d M Y') }}</td>
                            <td>{{ $stock->satuan }}</td>
                            <td class="text-end">{{ $stock->jumlah_awal ?? $stock->jumlah }}</td>
                            <td class="text-end">{{ $stock->histories->sum('jumlah') }}</td>
                            <td class="text-end"><span class="badge-soft {{ $badge }}">{{ $stock->jumlah }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-row">Belum ada data stok</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($stocks, 'links'))
    <div class="mt-3">{{ $stocks->links() }}</div>
    @endif
@endsection
