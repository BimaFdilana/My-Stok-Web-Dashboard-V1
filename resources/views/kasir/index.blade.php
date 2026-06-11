@extends('layouts.app')

@section('title', 'Kasir - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-cash-stack', 'title' => 'Kasir', 'subtitle' => 'Buat transaksi penjualan'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if($errors->any())
    <div class="alert-danger-soft">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="kasir-layout">
        <div class="kasir-menu">
            <div class="product-grid">
                @forelse($items as $item)
                <div class="product-card">
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                         onerror="this.onerror=null;this.src='{{ asset('img/default-product.jpg') }}';">
                    <div class="product-body">
                        <h6>{{ $item->nama }}</h6>
                        <div class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                        <button class="btn btn-primary btn-sm" style="width:100%;"
                            onclick="addItem('{{ $item->id }}', '{{ $item->nama }}', {{ $item->harga }})">
                            <i class="bi bi-plus-circle"></i> Tambah
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-row" style="grid-column:1/-1;">Belum ada menu</div>
                @endforelse
            </div>
        </div>

        <div class="kasir-cart">
            <div class="cart-head"><i class="bi bi-cart3"></i> Pesanan</div>
            <div class="cart-body">
                <table class="data-table" style="margin-bottom:12px;">
                    <thead>
                        <tr><th>Produk</th><th class="text-end">Qty</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody id="selected-items"></tbody>
                </table>
                <div class="cart-total">
                    <span>Total</span>
                    <span id="total-price">Rp 0</span>
                </div>
                <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="items" id="selectedItems">
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:12px;">
                        <i class="bi bi-bag-check"></i> Lakukan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stock Alert Modal -->
    <div class="modal fade" id="stockAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px; overflow:hidden; border:none;">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i> Stok Tidak Mencukupi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><p id="stockAlertMessage" class="mb-0"></p></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Stok</a>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .kasir-layout { display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; }
        @media(max-width:992px){ .kasir-layout{ grid-template-columns:1fr; } }
        .product-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
        @media(max-width:768px){ .product-grid{ grid-template-columns:repeat(2,1fr); } }
        @media(max-width:480px){ .product-grid{ grid-template-columns:1fr; } }
        .product-card { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(15,23,42,.06); overflow:hidden; transition:transform .2s; }
        .product-card:hover { transform:translateY(-2px); }
        .product-card img { width:100%; height:130px; object-fit:cover; }
        .product-body { padding:12px; text-align:center; }
        .product-body h6 { margin:0 0 4px; font-weight:600; font-size:14px; }
        .product-body .price { color:#0B3BB6; font-weight:700; margin-bottom:10px; font-size:14px; }
        .kasir-cart { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(15,23,42,.06); overflow:hidden; position:sticky; top:16px; }
        .cart-head { background:linear-gradient(135deg,#0B3BB6,#3e6bdf); color:#fff; padding:14px 18px; font-weight:600; }
        .cart-body { padding:16px; }
        .cart-total { display:flex; justify-content:space-between; align-items:center; padding-top:12px; border-top:1px solid var(--border-soft); font-weight:700; }
        .cart-total #total-price { color:#0B3BB6; font-size:18px; }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedItems = {};

        function addItem(id, name, price) {
            if (!selectedItems[id]) {
                selectedItems[id] = { id, name, price, quantity: 1 };
            } else {
                selectedItems[id].quantity += 1;
            }
            updateSelectedItemsTable();
        }

        function removeItem(id) {
            if (selectedItems[id]) {
                selectedItems[id].quantity -= 1;
                if (selectedItems[id].quantity <= 0) delete selectedItems[id];
            }
            updateSelectedItemsTable();
        }

        function updateSelectedItemsTable() {
            const tableBody = document.getElementById('selected-items');
            tableBody.innerHTML = '';
            let total_price = 0;
            Object.values(selectedItems).forEach(item => {
                tableBody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${item.name}</td>
                        <td class="text-end fw-bold" style="color:#0B3BB6;">${item.quantity}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-del btn-sm btn-icon" onclick="removeItem('${item.id}')"><i class="bi bi-dash"></i></button>
                            <button type="button" class="btn btn-primary btn-sm btn-icon" onclick="addItem('${item.id}','${item.name}',${item.price})"><i class="bi bi-plus"></i></button>
                        </td>
                    </tr>`);
                total_price += item.price * item.quantity;
            });
            document.getElementById('total-price').innerText = 'Rp ' + total_price.toLocaleString('id-ID');
            document.getElementById('selectedItems').value = JSON.stringify(Object.values(selectedItems));
        }

        @if (session('message')) alert("{{ session('message') }}"); @endif

        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (Object.keys(selectedItems).length === 0) {
                Swal.fire({ title: 'Error', text: 'Pilih minimal satu item', icon: 'error', confirmButtonColor: '#0B3BB6' });
                return;
            }
            $.ajax({
                url: "{{ route('transactions.store') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json', 'Content-Type': 'application/json' },
                data: JSON.stringify({ items: JSON.stringify(Object.values(selectedItems)) }),
                success: function(response) {
                    if (response.success) window.location.href = "{{ route('transactions.summary') }}";
                },
                error: function(xhr) {
                    try {
                        const response = xhr.responseJSON || JSON.parse(xhr.responseText);
                        if (xhr.status === 400) {
                            $('#stockAlertMessage').html(response.message);
                            new bootstrap.Modal(document.getElementById('stockAlertModal')).show();
                        } else {
                            Swal.fire({ title: 'Error', text: response.message || 'Terjadi kesalahan', icon: 'error', confirmButtonColor: '#0B3BB6' });
                        }
                    } catch (e) {
                        Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem', icon: 'error', confirmButtonColor: '#0B3BB6' });
                    }
                }
            });
        });

        @if (session('transaction_id'))
            window.open("{{ route('transactions.struk', session('transaction_id')) }}", '_blank');
        @endif
    </script>
@endsection
