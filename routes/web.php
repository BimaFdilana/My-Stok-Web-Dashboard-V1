<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanBarangMasukController;
use App\Http\Controllers\LaporanBarangKeluarController;
use App\Http\Controllers\LaporanTransaksiController;
use App\Http\Controllers\QrisController;
use App\Http\Controllers\KasirManagementController;

// Halaman Welcome
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    // Register dinonaktifkan — admin yang membuat akun kasir lewat menu Kelola Kasir
    // Route::get('/register', [LoginRegisterController::class, 'showRegisterForm'])->name('register');
    // Route::post('/register', [LoginRegisterController::class, 'register']);
    Route::get('/login', [LoginRegisterController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginRegisterController::class, 'login']);
});

// Logout
Route::post('/logout', [LoginRegisterController::class, 'logout'])->name('logout');

// Routes yang perlu auth
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('auth.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Pengaturan QRIS
    Route::get('/qris', [QrisController::class, 'index'])->name('qris.index');
    Route::post('/qris', [QrisController::class, 'store'])->name('qris.store');
    Route::put('/qris/{id}', [QrisController::class, 'update'])->name('qris.update');
    Route::delete('/qris/{id}', [QrisController::class, 'destroy'])->name('qris.destroy');

    // Kelola Kasir (admin only)
    Route::prefix('kasir-management')->name('kasir-management.')->group(function () {
        Route::get('/', [KasirManagementController::class, 'index'])->name('index');
        Route::get('/create', [KasirManagementController::class, 'create'])->name('create');
        Route::post('/', [KasirManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KasirManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KasirManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [KasirManagementController::class, 'destroy'])->name('destroy');

        Route::get('/{id}/permissions', [KasirManagementController::class, 'permissions'])->name('permissions');
        Route::put('/{id}/permissions', [KasirManagementController::class, 'updatePermissions'])->name('update-permissions');

        Route::get('/{id}/schedule', [KasirManagementController::class, 'schedule'])->name('schedule');
        Route::put('/{id}/schedule', [KasirManagementController::class, 'updateSchedule'])->name('update-schedule');

        Route::get('/{id}/sales', [KasirManagementController::class, 'sales'])->name('sales');
    });
});

// Produk (Item Management)
Route::resource('produks', ItemController::class);
Route::get('/produk', [ItemController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ItemController::class, 'show'])->name('produk.show');
Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

// Stock Management
Route::prefix('stocks')->name('stocks.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::get('/dashboard', [StockController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [StockController::class, 'create'])->name('create');
    Route::post('/store', [StockController::class, 'store'])->name('store');
    Route::get('/{id}', [StockController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [StockController::class, 'edit'])->name('edit');
    Route::put('/{id}', [StockController::class, 'update'])->name('update');
    Route::delete('/{id}', [StockController::class, 'destroy'])->name('destroy');
});

// Barang Masuk
Route::prefix('barangmasuk')->name('barangmasuk.')->group(function () {
    Route::get('/', [BarangMasukController::class, 'index'])->name('index');
    Route::get('/create', [BarangMasukController::class, 'create'])->name('create');
    Route::post('/', [BarangMasukController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BarangMasukController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BarangMasukController::class, 'update'])->name('update');
    Route::delete('/{id}', [BarangMasukController::class, 'destroy'])->name('destroy');
});

// Barang Keluar
Route::get('/barangkeluar', [StockOutController::class, 'index'])->name('barangkeluar.index');
Route::get('/stock-histories/{id}', [StockHistoryController::class, 'show'])->name('stock-histories.show');

// Transactions / Kasir
Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/summary', [TransactionController::class, 'summary'])->name('summary');
    Route::post('/process', [TransactionController::class, 'process'])->name('process');
    Route::post('/save-payment', [TransactionController::class, 'savePayment'])->name('save-payment');
    Route::post('/validate-stock', [TransactionController::class, 'validateStock'])->name('validate-stock');
    Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    Route::get('/{id}/struk', [TransactionController::class, 'struk'])->name('struk');
    Route::put('/{id}', [TransactionController::class, 'update'])->name('update');
    Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
});

// Laporan Barang Masuk
Route::get('/laporanmasuk', [LaporanBarangMasukController::class, 'index'])->name('laporanmasuk.index');
Route::get('/laporanmasuk/{tanggal}', [LaporanBarangMasukController::class, 'detail'])->name('laporanmasuk.detail');
Route::get('/laporan/barangmasuk/cetak/{tanggal}', [LaporanBarangMasukController::class, 'cetakPDF'])->name('laporan.barangmasuk.cetak');

// Laporan Barang Keluar
Route::get('/laporankeluar', [LaporanBarangKeluarController::class, 'index'])->name('laporankeluar.index');
Route::get('/laporankeluar/{tanggal}', [LaporanBarangKeluarController::class, 'detail'])->name('laporankeluar.detail');
Route::get('/laporan/barangkeluar/cetak/{tanggal}', [LaporanBarangKeluarController::class, 'cetakPDF'])->name('laporan.barangkeluar.cetak');

// Laporan Transaksi
Route::get('/laporan/transaksi', [LaporanTransaksiController::class, 'index'])->name('laporantransaksi.index');
Route::get('/laporan/transaksi/detail/{tanggal}', [LaporanTransaksiController::class, 'detail'])->name('laporantransaksi.detail');
Route::get('/laporan/transaksi/cetak/{tanggal}', [LaporanTransaksiController::class, 'cetak'])->name('laporantransaksi.cetak');
