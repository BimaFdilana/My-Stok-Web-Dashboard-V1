<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KasirApiController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\QrisController;
use App\Models\Ingredient;
use App\Models\Category;

// API AUTH (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// API publik (sementara dibiarkan terbuka untuk kompatibilitas)
Route::get('/dashboard', [DashboardController::class, 'apiDashboard']);
Route::get('/items', [ItemController::class, 'apiIndex']);
Route::get('/items/{id}', [ItemController::class, 'apiShow']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/items', [ItemController::class, 'apiStore']);
Route::delete('/items/{id}', [ItemController::class, 'apiDestroy']);
Route::post('/items/{id}', [ItemController::class, 'apiUpdate']);

Route::get('/stocks', [BarangMasukController::class, 'apiIndex']);
Route::get('/stocks/{id}', [BarangMasukController::class, 'apiShow']);
Route::post('/stocks', [BarangMasukController::class, 'apiStore']);
Route::put('/stocks/{id}', [BarangMasukController::class, 'apiUpdate']);
Route::delete('/stocks/{id}', [BarangMasukController::class, 'apiDestroy']);

Route::get('/ingredients', function () {
    return response()->json(Ingredient::all());
});

Route::get('/barang-keluar', [StockHistoryController::class, 'apiIndex']);

// API yang perlu auth (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    // Kasir
    Route::get('/kasir/items', [KasirApiController::class, 'items']);
    Route::post('/kasir/checkout', [KasirApiController::class, 'checkout']);
    Route::get('/kasir/receipt/{id}', [KasirApiController::class, 'receipt']);

    // Laporan
    Route::get('/laporan/barang-masuk', [LaporanController::class, 'barangMasuk']);
    Route::get('/laporan/barang-keluar', [LaporanController::class, 'barangKeluar']);
    Route::get('/laporan/transaksi', [LaporanController::class, 'transaksi']);

    // QRIS
    Route::get('/qris/active', [QrisController::class, 'active']);
});
