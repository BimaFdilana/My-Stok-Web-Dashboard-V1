@extends('layouts.app')

@section('title', 'Laporan Barang Keluar - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-file-earmark-arrow-up', 'title' => 'Laporan Barang Keluar', 'subtitle' => 'Rekap penggunaan bahan per tanggal'])

    <div class="data-card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th class="text-end">Total Item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanPerTanggal as $tanggal => $laporan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</td>
                        <td class="text-end">{{ count($laporan) }}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('laporankeluar.detail', ['tanggal' => $tanggal]) }}" class="btn btn-view btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                <a href="{{ route('laporan.barangkeluar.cetak', ['tanggal' => $tanggal]) }}" class="btn btn-edit btn-sm" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-row">Tidak ada data laporan barang keluar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
