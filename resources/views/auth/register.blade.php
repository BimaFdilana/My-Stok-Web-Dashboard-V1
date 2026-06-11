@extends('layouts.auth')

@section('title', 'Register - MyStock')

@section('content')
    <h2>Buat Akun</h2>
    <p class="subtitle">Daftarkan usaha Anda untuk mulai mengelola stok</p>

    @if ($errors->any())
        <div class="alert-soft" style="margin-bottom: 18px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <span style="display:block; font-size: 13px;">{{ $error }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-field">
            <label for="name">Nama Usaha</label>
            <input type="text" name="name" id="name" class="form-control"
                   placeholder="Contoh: Kedai Kopi ABC" value="{{ old('name') }}" required>
        </div>

        <div class="form-field">
            <label for="nama_pemilik">Nama Pemilik</label>
            <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control"
                   placeholder="Nama lengkap pemilik" value="{{ old('nama_pemilik') }}" required>
        </div>

        <div class="form-field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control"
                   placeholder="Username untuk login" value="{{ old('username') }}" required>
        </div>

        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   placeholder="email@contoh.com" value="{{ old('email') }}" required>
        </div>

        <div class="form-field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="Minimal 8 karakter" required>
                <i class="bi bi-eye-slash toggle-pass" id="togglePassword"></i>
            </div>
        </div>

        <div class="form-field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="input-wrap">
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control" placeholder="Ulangi password" required>
                <i class="bi bi-eye-slash toggle-pass" id="togglePasswordConfirm"></i>
            </div>
        </div>

        <button type="submit" class="btn-primary-grad">Daftar</button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pw = document.getElementById('password');
            const isHidden = pw.type === 'password';
            pw.type = isHidden ? 'text' : 'password';
            this.classList.toggle('bi-eye', isHidden);
            this.classList.toggle('bi-eye-slash', !isHidden);
        });
        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const pw = document.getElementById('password_confirmation');
            const isHidden = pw.type === 'password';
            pw.type = isHidden ? 'text' : 'password';
            this.classList.toggle('bi-eye', isHidden);
            this.classList.toggle('bi-eye-slash', !isHidden);
        });
    </script>
@endsection
