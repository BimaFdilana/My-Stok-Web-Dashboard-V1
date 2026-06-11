@extends('layouts.app')

@section('title', 'Edit Barang Masuk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-pencil-square', 'title' => 'Edit Barang Masuk', 'subtitle' => 'Perbarui data penerimaan stok'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('barangmasuk.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card">
        <form action="{{ route('barangmasuk.update', $stock->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-field">
                    <label for="ingredient_id">Nama Bahan</label>
                    <select name="ingredient_id" id="ingredient_id" class="form-control" required>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}" {{ $stock->ingredient_id == $ingredient->id ? 'selected' : '' }}>{{ $ingredient->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="category_id">Kategori</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $stock->category_id == $category->id ? 'selected' : '' }}>{{ $category->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $stock->jumlah }}" min="1" required>
                </div>
                <div class="form-field">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control" value="{{ $stock->satuan }}" required>
                </div>
                <div class="form-field">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $stock->tanggal->format('Y-m-d') }}" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update</button>
                <a href="{{ route('barangmasuk.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
@endsection
