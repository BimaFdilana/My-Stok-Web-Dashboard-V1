@extends('layouts.app')

@section('title', 'Detail Laporan Keluar - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-file-earmark-arrow-up', 'title' => 'Laporan Keluar', 'subtitle' => Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <button onclick="cetakPDF('{{ $tanggal }}')" class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> Cetak PDF</button>
            <a href="{{ route('laporankeluar.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="data-card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockHistories as $history)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $history->ingredient->nama ?? '-' }}</td>
                        <td>{{ $history->stock->category->nama ?? '-' }}</td>
                        <td class="text-end">{{ $history->jumlah }} {{ $history->unit }}</td>
                        <td>{{ $history->tanggal->format('H:i:s') }}</td>
                        <td>{{ $history->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-row">Tidak ada data untuk tanggal ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function cetakPDF(tanggal) {
            window.open(`/laporan/barangkeluar/cetak/${tanggal}`, '_blank');
        }
    </script>
@endsection
