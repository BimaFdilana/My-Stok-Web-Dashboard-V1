<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyStock')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo4.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-brand">
            <div class="auth-brand-content">
                <img src="{{ asset('img/logo4.png') }}" alt="MyStock Logo">
                <h1>MyStock</h1>
                <p>Kelola stok, transaksi, dan laporan usaha Anda dengan mudah dan cepat.</p>
            </div>
        </div>
        <div class="auth-panel">
            <div class="auth-card">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
