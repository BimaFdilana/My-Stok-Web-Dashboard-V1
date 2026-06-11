@extends('layouts.app')

@section('title', 'Atur Akses Kasir - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-shield-check', 'title' => 'Atur Akses Menu', 'subtitle' => 'Kasir: ' . $kasir->name])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('kasir-management.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="form-card">
        <p class="text-muted" style="margin-top:0;">Centang menu yang boleh diakses kasir. Dashboard & Kasir selalu aktif.</p>
        <form action="{{ route('kasir-management.update-permissions', $kasir->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="perm-grid">
                @foreach($menuOptions as $key => $label)
                    @php $locked = in_array($key, ['dashboard', 'kasir']); $checked = in_array($key, $currentPermissions) || $locked; @endphp
                    <label class="perm-item {{ $checked ? 'on' : '' }} {{ $locked ? 'locked' : '' }}">
                        <input type="checkbox" name="menus[]" value="{{ $key }}" {{ $checked ? 'checked' : '' }} {{ $locked ? 'disabled' : '' }}>
                        <span>{{ $label }}</span>
                        @if($locked)<i class="bi bi-lock-fill"></i>@endif
                    </label>
                @endforeach
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Akses</button>
            </div>
        </form>
    </div>

    <style>
        .perm-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
        @media(max-width:640px){ .perm-grid{ grid-template-columns:1fr; } }
        .perm-item { display:flex; align-items:center; gap:10px; padding:12px 14px; border:1px solid var(--border-soft); border-radius:10px; cursor:pointer; transition:all .15s; }
        .perm-item:hover { border-color:var(--brand-primary); }
        .perm-item.on { background:rgba(11,59,182,.05); border-color:var(--brand-primary); }
        .perm-item.locked { opacity:.75; cursor:not-allowed; background:var(--bg-soft); }
        .perm-item input { width:18px; height:18px; accent-color:var(--brand-primary); }
        .perm-item span { font-weight:500; flex:1; }
        .perm-item i { color:var(--text-muted); font-size:13px; }
    </style>

    <script>
        document.querySelectorAll('.perm-item input:not([disabled])').forEach(cb => {
            cb.addEventListener('change', function() {
                this.closest('.perm-item').classList.toggle('on', this.checked);
            });
        });
    </script>
@endsection
