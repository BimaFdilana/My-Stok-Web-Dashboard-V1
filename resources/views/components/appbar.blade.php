@php
    $color = $color ?? 'blue';
    $icon = $icon ?? 'bi-grid';
    $title = $title ?? 'MyStock';
    $subtitle = $subtitle ?? null;
@endphp

<div class="app-header {{ $color }}">
    <button class="sidebar-toggle" onclick="openSidebar()"><i class="bi bi-list"></i></button>
    <div class="head-icon"><i class="bi {{ $icon }}"></i></div>
    <div class="app-header-info">
        <h4>{{ $title }}</h4>
        @if($subtitle)
            <p>{{ $subtitle }}</p>
        @else
            <p>{{ now()->translatedFormat('l, d F Y') }}</p>
        @endif
    </div>
    <div class="app-header-meta">
        <span class="d-block">{{ Auth::user()->name ?? '' }}</span>
        <a href="{{ route('profile.show') }}"><i class="bi bi-person-circle me-1"></i> Profil</a>
    </div>
</div>
