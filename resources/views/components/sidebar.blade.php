@php
    $role = Auth::user()->role ?? 'kasir';
    $current = Route::currentRouteName();

    $kasirMenus = [];
    if ($role === 'kasir') {
        $kasirMenus = Auth::user()->permissions->pluck('menu_key')->toArray();
    }
@endphp

<div class="sidebar-brand">
    <img src="{{ asset('img/logo4.png') }}" alt="Logo">
    <span class="sidebar-brand-name">MyStok</span>
</div>

<nav>
    <div class="sidebar-section">
        <div class="sidebar-title">Menu</div>

        @if($role === 'admin' || in_array('dashboard', $kasirMenus))
        <a href="{{ route('auth.dashboard') }}" class="sidebar-link {{ $current === 'auth.dashboard' ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        @endif

        @if($role === 'admin' || in_array('kasir', $kasirMenus))
        <a href="{{ route('transactions.index') }}" class="sidebar-link {{ str_starts_with($current, 'transactions') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Kasir
        </a>
        @endif

        @if($role === 'admin' || in_array('barang', $kasirMenus))
        <a href="{{ route('produks.index') }}" class="sidebar-link {{ str_starts_with($current, 'produks') ? 'active' : '' }}">
            <i class="bi bi-basket3"></i> Barang
        </a>
        @endif

        @if($role === 'admin' || in_array('qris', $kasirMenus))
        <a href="{{ route('qris.index') }}" class="sidebar-link {{ $current === 'qris.index' ? 'active' : '' }}">
            <i class="bi bi-qr-code"></i> Pengaturan QRIS
        </a>
        @endif
    </div>

    @if($role === 'admin' || in_array('stok', $kasirMenus) || in_array('barang_masuk', $kasirMenus) || in_array('barang_keluar', $kasirMenus))
    <div class="sidebar-section">
        <div class="sidebar-title">Inventori</div>

        @if($role === 'admin' || in_array('stok', $kasirMenus))
        <a href="{{ route('stocks.index') }}" class="sidebar-link {{ $current === 'stocks.index' ? 'active' : '' }}">
            <i class="bi bi-archive"></i> Stok
        </a>
        @endif

        @if($role === 'admin' || in_array('barang_masuk', $kasirMenus))
        <a href="{{ route('barangmasuk.index') }}" class="sidebar-link {{ str_starts_with($current, 'barangmasuk') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-square"></i> Barang Masuk
        </a>
        @endif

        @if($role === 'admin' || in_array('barang_keluar', $kasirMenus))
        <a href="{{ route('barangkeluar.index') }}" class="sidebar-link {{ $current === 'barangkeluar.index' ? 'active' : '' }}">
            <i class="bi bi-arrow-up-square"></i> Barang Keluar
        </a>
        @endif
    </div>
    @endif

    @if($role === 'admin' || in_array('laporan_masuk', $kasirMenus) || in_array('laporan_keluar', $kasirMenus) || in_array('laporan_transaksi', $kasirMenus))
    <div class="sidebar-section">
        <div class="sidebar-title">Laporan</div>

        @if($role === 'admin' || in_array('laporan_masuk', $kasirMenus))
        <a href="{{ route('laporanmasuk.index') }}" class="sidebar-link {{ $current === 'laporanmasuk.index' ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-down"></i> Laporan Masuk
        </a>
        @endif

        @if($role === 'admin' || in_array('laporan_keluar', $kasirMenus))
        <a href="{{ route('laporankeluar.index') }}" class="sidebar-link {{ $current === 'laporankeluar.index' ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-up"></i> Laporan Keluar
        </a>
        @endif

        @if($role === 'admin' || in_array('laporan_transaksi', $kasirMenus))
        <a href="{{ route('laporantransaksi.index') }}" class="sidebar-link {{ str_starts_with($current, 'laporantransaksi') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Laporan Transaksi
        </a>
        @endif
    </div>
    @endif

    @if($role === 'admin')
    <div class="sidebar-section">
        <div class="sidebar-title">Kelola Kasir</div>
        <a href="{{ route('kasir-management.index') }}" class="sidebar-link {{ str_starts_with($current, 'kasir-management') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Daftar Kasir
        </a>
    </div>
    @endif

    <div class="sidebar-logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</nav>
