@extends('layouts.app')

@section('title', 'Detail Laporan Masuk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-file-earmark-arrow-down', 'title' => 'Laporan Masuk', 'subtitle' => Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <button onclick="window.open('/laporan/barangmasuk/cetak/{{ $tanggal }}','_blank')" class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> Cetak PDF</button>
            <a href="{{ route('laporanmasuk.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="data-card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah Masuk</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangMasuk as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['kode_barang'] }}</td>
                        <td>{{ $item['nama_barang'] }}</td>
                        <td>{{ $item['kategori'] }}</td>
                        <td class="text-end">{{ $item['barang_masuk'] }}</td>
                        <td>{{ $item['satuan'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-row">Tidak ada data untuk tanggal ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
