@extends('layouts.app')

@section('title', 'Edit Profil - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-person-gear', 'title' => 'Edit Profil', 'subtitle' => 'Perbarui data dan password akun'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('profile.show') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="form-card">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="text-align:center; margin-bottom:20px;">
                <img src="{{ $user->foto ? asset('storage/profile_photos/'.$user->foto) : asset('img/default-profile.png') }}"
                     alt="Profile" class="profile-avatar" style="width:100px; height:100px; margin-bottom:12px;">
                <div class="form-field" style="max-width:300px; margin:0 auto;">
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <span class="form-hint">Format: JPEG, PNG. Max 2MB</span>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama Usaha</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-field">
                    <label for="nama_pemilik">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" value="{{ old('nama_pemilik', $user->nama_pemilik) }}" required>
                </div>
                <div class="form-field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
                <a href="{{ route('profile.show') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>

    <div class="form-card mt-4">
        <h6 style="margin:0 0 16px; font-weight:600; font-size:16px;"><i class="bi bi-key"></i> Update Password</h6>
        <form action="{{ route('profile.update-password') }}" method="POST">
            @csrf
            <div class="form-field">
                <label for="current_password">Password Saat Ini</label>
                <div class="input-wrap">
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                    <i class="bi bi-eye-slash toggle-pass" onclick="togglePw('current_password')"></i>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-field">
                    <label for="new_password">Password Baru</label>
                    <div class="input-wrap">
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                        <i class="bi bi-eye-slash toggle-pass" onclick="togglePw('new_password')"></i>
                    </div>
                </div>
                <div class="form-field">
                    <label for="new_password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        <i class="bi bi-eye-slash toggle-pass" onclick="togglePw('new_password_confirmation')"></i>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-key"></i> Update Password</button>
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
