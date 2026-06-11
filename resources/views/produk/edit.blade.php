@extends('layouts.app')

@section('title', 'Edit Produk - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-pencil-square', 'title' => 'Edit Produk', 'subtitle' => $item->nama])

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
        <form action="{{ route('produks.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label for="kode">Kode Produk</label>
                    <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode', $item->kode) }}" required>
                </div>
                <div class="form-field">
                    <label for="nama">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $item->nama) }}" required>
                </div>
                <div class="form-field">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $item->harga) }}" required>
                </div>
                <div class="form-field">
                    <label>Foto</label>
                    @if($item->foto)
                        <img src="{{ asset('storage/'.$item->foto) }}" id="fotoPreview" class="detail-img mb-2" style="max-width:120px; display:block;">
                    @else
                        <img id="fotoPreview" class="detail-img mb-2" style="max-width:120px; display:none;">
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <span class="form-hint">Kosongkan jika tidak ingin mengubah foto</span>
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
                        @foreach($item->ingredients as $index => $ingredient)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><input type="text" name="ingredients[{{ $index }}][kode]" class="form-control" value="{{ $ingredient->kode }}" required></td>
                            <td><input type="text" name="ingredients[{{ $index }}][nama]" class="form-control" value="{{ $ingredient->nama }}" required></td>
                            <td><input type="number" name="ingredients[{{ $index }}][stok]" class="form-control" value="{{ $ingredient->pivot->jumlah }}" required></td>
                            <td>
                                <select name="ingredients[{{ $index }}][satuan]" class="form-control">
                                    <option value="gram" {{ $ingredient->pivot->satuan == 'gram' ? 'selected' : '' }}>gram</option>
                                    <option value="ml" {{ $ingredient->pivot->satuan == 'ml' ? 'selected' : '' }}>ml</option>
                                    <option value="pcs" {{ $ingredient->pivot->satuan == 'pcs' ? 'selected' : '' }}>pcs</option>
                                </select>
                            </td>
                            <td><button type="button" class="btn btn-del btn-sm" onclick="removeIngredientRow(this)"><i class="bi bi-trash"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="addIngredientRow()"><i class="bi bi-plus-lg"></i> Tambah Bahan</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Produk</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('fotoPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        function addIngredientRow() {
            const table = document.querySelector('#ingredient_table tbody');
            const rowCount = table.rows.length;
            const row = table.insertRow();
            row.innerHTML = `
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
            button.closest('tr').remove();
            const rows = document.querySelectorAll('#ingredient_table tbody tr');
            rows.forEach((row, index) => {
                row.cells[0].innerText = index + 1;
                const inputs = row.getElementsByTagName('input');
                const selects = row.getElementsByTagName('select');
                for (let input of inputs) input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                for (let select of selects) select.name = select.name.replace(/\[\d+\]/, `[${index}]`);
            });
        }
    </script>
@endsection
