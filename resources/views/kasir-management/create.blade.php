@extends('layouts.app')

@section('title', 'Tambah Kasir - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-person-plus', 'title' => 'Tambah Kasir', 'subtitle' => 'Buat akun kasir baru'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('kasir-management.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card">
        <form action="{{ route('kasir-management.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-field">
                    <label for="nama_pemilik">Nama Lengkap</label>
                    <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" value="{{ old('nama_pemilik') }}" required>
                </div>
                <div class="form-field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        <i class="bi bi-eye-slash toggle-pass" onclick="togglePw('password')"></i>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
                <a href="{{ route('kasir-management.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>

    <script>
    function togglePw(id) {
        const el = document.getElementById(id);
        const icon = el.nextElementSibling;
        const isHidden = el.type === 'password';
        el.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('bi-eye', isHidden);
        icon.classList.toggle('bi-eye-slash', !isHidden);
    }
    </script>
@endsection
