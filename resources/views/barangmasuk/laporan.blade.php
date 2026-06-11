<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Masuk - {{ $tanggal }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        .bg-gradient {
            background: linear-gradient(to right, #588A46, #68BE49);
        }

        .sidebar {
            font-size: 14px;
            border-right: 2px solid #ddd;
            padding-right: 10px;
        }

        .sidebar .nav-link {
            color: #305822;
        }

        .sidebar .nav-link i {
            color: #305822;
            margin-right: 8px;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(48, 88, 34, 0.1);
        }

        .header {
            background: linear-gradient(to right, #588A46, #68BE49);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header {
            background: linear-gradient(to right, #588A46, #68BE49);
        }

        .card-main {
            background-color: #FCF9F2; /* Warna krem */
            padding: 20px;
            border-radius: 10px;
        }

        .card-data {
            background-color: #EDF3EB;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-data h5 {
            font-size: 16px;
            font-weight: bold;
        }

        .signature {
            margin-top: 30px;
            text-align: center;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar vh-100 p-3">
                <div class="text-center mb-4">
                    <a href="/profile">
                        <img src="{{ asset('img/logo2.png') }}" alt="Logo" class="img-fluid rounded-circle mb-2"
                            style="width: 100px;">
                    </a>
                    <h4>My Stock</h4>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <span class="nav-link">
                            <i class="bi bi-house-door-fill"></i> Dashboard
                        </span>
                    </li>
                    <li class="nav-item mb-2">
                        <span class="nav-link">
                            <i class="bi bi-box2"></i> Master
                        </span>
                        <ul class="list-unstyled ps-3">
                            <li><a href="{{ route('transactions.index') }}" class="nav-link">
                                <i class="bi bi-cash"></i> Kasir
                            </a></li>
                            <li><a href="{{ route('produks.index') }}" class="nav-link">
                                <i class="bi bi-basket3"></i> Barang
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item mb-2">
                        <span class="nav-link">
                            <i class="bi bi-clipboard"></i> Inventori
                        </span>
                        <ul class="list-unstyled ps-3">
                            <li><a href="{{ route('stocks.index') }}" class="nav-link">
                                <i class="bi bi-archive"></i> Stok
                            </a></li>
                            <li><a href="{{ route('barangmasuk.index') }}" class="nav-link">
                                <i class="bi bi-arrow-down-square"></i> Barang Masuk
                            </a></li>
                            <li><a href="{{ route('barangkeluar.index') }}" class="nav-link">
                                <i class="bi bi-arrow-up-square"></i> Barang Keluar
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-gear-fill"></i> Pengaturan
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <div class="col-md-10 p-4">
                <div class="header p-3 rounded mb-4">
                    <h2><i class="bi bi-arrow-down-square"></i> Laporan Barang Masuk</h2>
                </div>

                <!-- Main Card -->
                <div class="card card-main">
                    <h4 class="mb-4">Detail Laporan</h4>
                    <h4 class="text-muted">Tanggal: {{ $tanggal }}</h4>
                    <div class="row">
                        @foreach($stocks as $stock)
                            <div class="col-md-6">
                                <div class="card card-data p-3">
                                    <h5>Produk: {{ $stock->ingredient->nama }}</h5>
                                    <p>
                                        <strong>Kode:</strong> {{ $stock->ingredient->kode }}<br>
                                        <strong>Stok Awal:</strong> {{ $stock->stok_awal }}<br>
                                        <strong>Barang Masuk:</strong> {{ $stock->jumlah }}<br>
                                        <strong>Stok Akhir:</strong> {{ $stock->stok_akhir }}<br>
                                        <strong>Terakhir Diperbarui:</strong> {{ $stock->tanggal }}<br>
                                        <strong>Waktu:</strong> {{ $stock->waktu }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Print Button -->
                <button class="btn btn-primary print-button" onclick="window.print()">
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
