@extends('layouts.app')

@section('title', 'Barang Masuk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-arrow-down-square', 'title' => 'Barang Masuk', 'subtitle' => 'Data penerimaan stok bahan'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <form action="{{ route('laporanmasuk.index') }}" method="GET" style="display:flex; align-items:center; gap:8px;">
                <input type="date" name="tanggal" class="form-control" style="width:auto;" required>
                <button type="submit" class="btn btn-outline btn-sm"><i class="bi bi-file-earmark-arrow-down"></i> Laporan</button>
            </form>
            <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="data-card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah Awal</th>
                        <th class="text-end">Jumlah Saat Ini</th>
                        <th>Satuan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $stock->ingredient->kode }}</td>
                        <td>{{ $stock->ingredient->nama }}</td>
                        <td>{{ $stock->category->nama }}</td>
                        <td class="text-end">{{ $stock->jumlah_awal }}</td>
                        <td class="text-end">
                            @php $badge = $stock->jumlah > 100 ? 'aman' : ($stock->jumlah >= 10 ? 'medium' : 'kritis'); @endphp
                            <span class="badge-soft {{ $badge }}">{{ $stock->jumlah }}</span>
                        </td>
                        <td>{{ $stock->satuan }}</td>
                        <td>{{ $stock->tanggal->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('barangmasuk.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-del btn-sm btn-icon"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="empty-row">Belum ada data barang masuk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
