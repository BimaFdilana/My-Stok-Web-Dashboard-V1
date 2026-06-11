@extends('layouts.app')

@section('title', 'Barang Keluar - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-arrow-up-square', 'title' => 'Barang Keluar', 'subtitle' => 'Riwayat penggunaan bahan baku'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <form action="{{ route('laporankeluar.index') }}" method="GET" style="display:flex; align-items:center; gap:8px;">
                <input type="date" name="tanggal" class="form-control" style="width:auto;" required>
                <button type="submit" class="btn btn-outline btn-sm"><i class="bi bi-file-earmark-arrow-up"></i> Laporan</button>
            </form>
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
                        <th>Satuan</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockHistories as $history)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $history->ingredient->nama ?? '-' }}</td>
                        <td>{{ $history->stock->category->nama ?? '-' }}</td>
                        <td class="text-end">{{ $history->jumlah }}</td>
                        <td>{{ $history->unit ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->tanggal)->format('d M Y') }}</td>
                        <td>{{ $history->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="empty-row">Belum ada data barang keluar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
