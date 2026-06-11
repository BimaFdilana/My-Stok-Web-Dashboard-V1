@extends('layouts.app')

@section('title', 'Pengaturan QRIS - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-qr-code', 'title' => 'Pengaturan QRIS', 'subtitle' => 'Kelola QR code untuk pembayaran QRIS'])

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:20px; align-items:start;">
        <div class="detail-card" style="max-width:none; text-align:center;">
            <h6 style="font-weight:600; margin-bottom:16px;">QRIS Aktif</h6>
            @if($qris)
                <img src="{{ asset('storage/' . $qris->foto) }}" alt="QR" style="max-width:220px; border-radius:10px; border:1px solid var(--border-soft); padding:8px; margin-bottom:12px;">
                <p style="margin:0; font-weight:600;">{{ $qris->nama_merchant }}</p>
                <p class="text-muted" style="font-size:13px;">{{ $qris->keterangan ?? '-' }}</p>
                <form action="{{ route('qris.destroy', $qris->id) }}" method="POST" onsubmit="return confirm('Hapus QRIS ini?')" style="margin-top:12px;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-del btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                </form>
            @else
                <p class="text-muted">Belum ada QRIS yang diupload.</p>
            @endif
        </div>

        <div class="form-card" style="max-width:none;">
            <h6 style="font-weight:600; margin-bottom:16px;">{{ $qris ? 'Perbarui QRIS' : 'Upload QRIS Baru' }}</h6>
            <form action="{{ $qris ? route('qris.update', $qris->id) : route('qris.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($qris) @method('PUT') @endif

                <div class="form-field">
                    <label for="nama_merchant">Nama Merchant</label>
                    <input type="text" name="nama_merchant" id="nama_merchant" class="form-control" value="{{ old('nama_merchant', $qris->nama_merchant ?? '') }}" required>
                </div>
                <div class="form-field">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="2">{{ old('keterangan', $qris->keterangan ?? '') }}</textarea>
                </div>
                <div class="form-field">
                    <label for="foto">Foto QR @if($qris)<span class="form-hint">(kosongkan jika tidak diganti)</span>@endif</label>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" {{ $qris ? '' : 'required' }}>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media(max-width:768px){ div[style*="grid-template-columns:1fr 1.5fr"]{ grid-template-columns:1fr !important; } }
    </style>
@endsection
