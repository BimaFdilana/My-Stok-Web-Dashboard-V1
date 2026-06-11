@extends('layouts.app')

@section('title', 'Kelola Kasir - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-people', 'title' => 'Kelola Kasir', 'subtitle' => 'Manajemen akun & akses kasir'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('kasir-management.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus"></i> Tambah Kasir</a>
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
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Akses Menu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasirs as $kasir)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kasir->name }}</td>
                        <td>{{ $kasir->username }}</td>
                        <td>{{ $kasir->email }}</td>
                        <td><span class="badge-soft aman">{{ $kasir->permissions->count() }} menu</span></td>
                        <td>
                            <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">
                                <a href="{{ route('kasir-management.permissions', $kasir->id) }}" class="btn btn-view btn-sm btn-icon" title="Atur Akses"><i class="bi bi-shield-check"></i></a>
                                <a href="{{ route('kasir-management.schedule', $kasir->id) }}" class="btn btn-light btn-sm btn-icon" title="Jam Kerja"><i class="bi bi-clock"></i></a>
                                <a href="{{ route('kasir-management.sales', $kasir->id) }}" class="btn btn-light btn-sm btn-icon" title="Riwayat Penjualan"><i class="bi bi-receipt"></i></a>
                                <a href="{{ route('kasir-management.edit', $kasir->id) }}" class="btn btn-edit btn-sm btn-icon" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('kasir-management.destroy', $kasir->id) }}" method="POST" onsubmit="return confirm('Hapus kasir ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-del btn-sm btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-row">Belum ada akun kasir</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
