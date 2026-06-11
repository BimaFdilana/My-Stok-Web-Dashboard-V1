<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $stocks = Stock::with(['ingredient', 'category'])->get();
        $totalBarang = Item::count();
        $totalBarangMasuk = Stock::sum('jumlah');
        $totalBarangKeluar = StockHistory::sum('jumlah');

        $transaksiHariIni = Transaction::whereDate('created_at', $today)->get();
        $totalTransaksiHariIni = $transaksiHariIni->count();
        $pendapatanHariIni = $transaksiHariIni->sum('total');
        $totalTunai = $transaksiHariIni->where('payment_method', 'cash')->sum('total');
        $totalQris = $transaksiHariIni->where('payment_method', 'qris')->sum('total');

        $stokKritis = $stocks->filter(fn($s) => $s->jumlah < 10);

        return view('auth.dashboard', compact(
            'user', 'stocks', 'totalBarang', 'totalBarangMasuk',
            'totalBarangKeluar', 'totalTransaksiHariIni', 'pendapatanHariIni',
            'totalTunai', 'totalQris', 'stokKritis'
        ));
    }

    public function updateStock(Request $request, $id)
    {
        $stock = Stock::find($id);
        $stock->jumlah = $request->input('stock');
        $stock->save();

        return response()->json(['success' => true]);
    }

    public function apiDashboard()
    {
        $stocks = Stock::with(['ingredient', 'category'])->get();

        $totalBarang = Item::count();
        $totalBarangMasuk = Stock::sum('jumlah');
        $totalBarangKeluar = StockHistory::sum('jumlah');

        return response()->json([
            'success' => true,
            'total_barang' => $totalBarang,
            'total_barang_masuk' => $totalBarangMasuk,
            'total_barang_keluar' => $totalBarangKeluar,
            'stocks' => $stocks
        ]);
    }
}
