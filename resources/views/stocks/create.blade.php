@extends('layouts.app')

@section('title', 'Tambah Stok - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-plus-square', 'title' => 'Tambah Stok', 'subtitle' => 'Input data stok bahan baru'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('stocks.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card">
        <form action="{{ route('stocks.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label for="ingredient_id">Nama Bahan</label>
                    <select name="ingredient_id" id="ingredient_id" class="form-control" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->nama ?? $ingredient->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="category_id">Kategori</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama ?? $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                </div>
                <div class="form-field">
                    <label for="satuan">Satuan</label>
                    <select name="satuan" id="satuan" class="form-control" required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="gram">gram</option>
                        <option value="ml">ml</option>
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="liter">liter</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
                <a href="{{ route('stocks.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
@endsection
