@extends('layouts.auth')

@section('title', 'Login - MyStock')

@section('content')
    <h2>Selamat Datang</h2>
    <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

    @if (session('success'))
        <div class="alert-soft" style="background: #dcfce7; color: #166534; border-color: #bbf7d0;">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-soft" style="margin-bottom: 18px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control"
                   placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="form-field">
            <label for="password">Kata Sandi</label>
            <div class="input-wrap">
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="Masukkan kata sandi" required>
                <i class="bi bi-eye-slash toggle-pass" id="togglePassword"></i>
            </div>
        </div>

        <button type="submit" class="btn-primary-grad">Masuk</button>
    </form>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pw = document.getElementById('password');
            const isHidden = pw.type === 'password';
            pw.type = isHidden ? 'text' : 'password';
            this.classList.toggle('bi-eye', isHidden);
            this.classList.toggle('bi-eye-slash', !isHidden);
        });
    </script>
@endsection
