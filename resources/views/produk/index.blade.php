@extends('layouts.app')

@section('title', 'Barang - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-basket3', 'title' => 'Daftar Barang', 'subtitle' => 'Kelola menu dan produk toko'])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <select id="categorySelect" class="form-control" style="width:auto;">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                @endforeach
            </select>
            <a href="#" id="addDataLink" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
        </div>
    </div>

    <div id="categoryError" class="alert-danger-soft" style="display:none;">Silakan pilih kategori terlebih dahulu.</div>

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="product-grid">
        @forelse($items as $item)
        <div class="product-card">
            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                 onerror="this.onerror=null;this.src='{{ asset('img/default-product.jpg') }}';">
            <div class="product-body">
                <h6>{{ $item->nama }}</h6>
                <div class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                <div class="product-actions">
                    <a href="#" class="btn btn-view btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}" title="Detail"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('produks.edit', $item->id) }}" class="btn btn-edit btn-sm btn-icon" title="Edit"><i class="bi bi-pencil-square"></i></a>
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="flex-grow:1;" onsubmit="return confirm('Yakin hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-del btn-sm" style="width:100%;"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px; overflow:hidden;">
                    <div class="modal-header text-white" style="background:linear-gradient(135deg,#0B3BB6,#3e6bdf); border:none;">
                        <h5 class="modal-title"><i class="bi bi-cup-hot-fill me-2"></i>{{ $item->nama }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <img src="{{ $item->foto ? asset('storage/'.$item->foto) : asset('img/default-product.jpg') }}" class="img-fluid rounded" style="max-height:220px;">
                            </div>
                            <div class="col-md-8">
                                <p><strong>Kode:</strong> {{ $item->kode }}</p>
                                <p><strong>Harga:</strong> Rp {{ number_format($item->harga,0,',','.') }}</p>
                                <p><strong>Kategori:</strong> {{ $item->category->nama ?? $item->kategori_id }}</p>
                                <h6 class="mt-3">Bahan Baku</h6>
                                <ul class="list-group">
                                    @foreach($item->ingredients as $ingredient)
                                    <li class="list-group-item d-flex justify-content-between">
                                        {{ $ingredient->nama }}
                                        <span class="badge bg-primary">{{ $ingredient->pivot->jumlah }} {{ $ingredient->pivot->satuan }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-row" style="grid-column:1/-1;">Belum ada produk</div>
        @endforelse
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const categorySelect = document.getElementById('categorySelect');
        const addDataLink = document.getElementById('addDataLink');
        const categoryError = document.getElementById('categoryError');

        addDataLink.addEventListener('click', function(e) {
            e.preventDefault();
            const selected = categorySelect.value;
            if (!selected) { categoryError.style.display = 'block'; return; }
            categoryError.style.display = 'none';
            window.location.href = `{{ route('produks.create') }}?category=${selected}`;
        });
    </script>

    <style>
        .product-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
        @media(max-width:992px){ .product-grid{ grid-template-columns:repeat(3,1fr); } }
        @media(max-width:768px){ .product-grid{ grid-template-columns:repeat(2,1fr); } }
        @media(max-width:480px){ .product-grid{ grid-template-columns:1fr; } }
        .product-card { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(15,23,42,.06); overflow:hidden; transition:transform .2s, box-shadow .2s; }
        .product-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(15,23,42,.1); }
        .product-card img { width:100%; height:160px; object-fit:cover; }
        .product-body { padding:14px; }
        .product-body h6 { margin:0 0 4px; font-weight:600; font-size:15px; }
        .product-body .price { color:#0B3BB6; font-weight:700; margin-bottom:12px; }
        .product-actions { display:flex; gap:6px; align-items:stretch; }
    </style>
@endsection
