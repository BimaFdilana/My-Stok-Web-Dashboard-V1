<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - MyStock')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo4.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-shell">
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

        <aside class="app-sidebar" id="appSidebar">
            @include('components.sidebar')
        </aside>

        <main class="app-main">
            @yield('content')
        </main>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('appSidebar').classList.add('open');
            document.getElementById('sidebarBackdrop').classList.add('open');
        }
        function closeSidebar() {
            document.getElementById('appSidebar').classList.remove('open');
            document.getElementById('sidebarBackdrop').classList.remove('open');
        }
    </script>
</body>
</html>
