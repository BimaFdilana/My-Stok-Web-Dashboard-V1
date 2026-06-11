@extends('layouts.app')

@section('title', 'Tambah Produk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-basket3', 'title' => 'Tambah Produk', 'subtitle' => 'Kategori: ' . $category->nama])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('produks.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card full">
        <form action="{{ route('produks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kategori_id" value="{{ $category->id }}">

            <div class="form-grid">
                <div class="form-field">
                    <label for="kode">Kode Produk</label>
                    <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode') }}" required>
                </div>
                <div class="form-field">
                    <label for="nama">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="form-field">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" required>
                </div>
                <div class="form-field">
                    <label for="foto">Foto Produk</label>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <img id="fotoPreview" class="detail-img mt-2" style="display:none; max-width:160px;" alt="Preview">
                </div>
            </div>

            <h6 style="margin:22px 0 12px; font-weight:600;">Bahan Baku</h6>
            <div style="overflow-x:auto;">
                <table class="data-table" id="ingredient_table" style="background:var(--bg-soft); border-radius:8px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="text" name="ingredients[0][kode]" class="form-control" required></td>
                            <td><input type="text" name="ingredients[0][nama]" class="form-control" required></td>
                            <td><input type="number" name="ingredients[0][stok]" class="form-control" required></td>
                            <td>
                                <select name="ingredients[0][satuan]" class="form-control">
                                    <option value="gram">gram</option>
                                    <option value="ml">ml</option>
                                    <option value="pcs">pcs</option>
                                </select>
                            </td>
                            <td><button type="button" class="btn btn-del btn-sm" onclick="removeIngredientRow(this)"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="addIngredientRow()"><i class="bi bi-plus-lg"></i> Tambah Bahan</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Produk</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('fotoPreview');
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        function addIngredientRow() {
            const table = document.getElementById('ingredient_table').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><input type="text" name="ingredients[${rowCount}][kode]" class="form-control" required></td>
                <td><input type="text" name="ingredients[${rowCount}][nama]" class="form-control" required></td>
                <td><input type="number" name="ingredients[${rowCount}][stok]" class="form-control" required></td>
                <td>
                    <select name="ingredients[${rowCount}][satuan]" class="form-control">
                        <option value="gram">gram</option>
                        <option value="ml">ml</option>
                        <option value="pcs">pcs</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-del btn-sm" onclick="removeIngredientRow(this)"><i class="bi bi-trash"></i></button></td>
            `;
        }

        function removeIngredientRow(button) {
            const row = button.closest('tr');
            row.remove();
            const tbody = document.getElementById('ingredient_table').getElementsByTagName('tbody')[0];
            const rows = tbody.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                rows[i].cells[0].textContent = i + 1;
                const inputs = rows[i].getElementsByTagName('input');
                const selects = rows[i].getElementsByTagName('select');
                for (let input of inputs) input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
                for (let select of selects) select.name = select.name.replace(/\[\d+\]/, `[${i}]`);
            }
        }
    </script>
@endsection
