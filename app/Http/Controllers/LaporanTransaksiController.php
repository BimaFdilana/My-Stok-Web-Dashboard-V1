<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class LaporanTransaksiController extends Controller
{
    /**
     * Menampilkan halaman utama laporan transaksi
     */
    public function index()
    {
        // Ambil semua transaksi dengan relasi ke details
        $laporanHarian = Transaction::with('details')
            ->get()
            ->groupBy(function ($transaction) {
                // Kelompokkan berdasarkan tanggal transaksi
                return $transaction->created_at->toDateString();
            })
            ->map(function ($transactions, $tanggal) {
                // Hitung total item yang terjual dan pendapatan
                $totalItemsSold = $transactions->flatMap->details->sum('quantity'); // Total item terjual
                $totalRevenue = $transactions->flatMap->details->sum(function ($detail) {
                    return $detail->quantity * $detail->price; // Total pendapatan
                });

                return [
                    'tanggal' => $tanggal,
                    'total_items_sold' => $totalItemsSold,
                    'total_revenue' => $totalRevenue,
                ];
            });

        // Kirim data ke view
        return view('laporantransaksi.index', compact('laporanHarian'));
    }

    /**
     * Menampilkan detail laporan transaksi berdasarkan tanggal
     */
   public function detail($tanggal)
{
    $transactions = Transaction::whereDate('created_at', $tanggal)
        ->with('details.item')
        ->get();

    $itemsSummary = $transactions->flatMap->details
        ->groupBy('item_id')
        ->map(function ($details) {

            $firstDetail = $details->first();

            return (object)[
                'name' => $firstDetail->item->nama ?? 'Item Tidak Ditemukan',
                'total_quantity' => $details->sum('quantity'),
                'total_revenue' => $details->sum(function ($detail) {
                    return $detail->quantity * $detail->price;
                }),
            ];
        })
        ->values();

    $totalCash = $transactions->where('payment_method', 'cash')->sum('total');
    $totalQris = $transactions->where('payment_method', 'qris')->sum('total');

    return view(
        'laporantransaksi.detail',
        compact('transactions', 'itemsSummary', 'tanggal', 'totalCash', 'totalQris')
    );
}

    /**
     * Cetak laporan transaksi ke PDF
     */
  public function cetak($tanggal)
{
    $transactions = Transaction::whereDate('created_at', $tanggal)
        ->with('details.item')
        ->get();

    $itemsSummary = $transactions->flatMap->details
        ->groupBy('item_id')
        ->map(function ($details) {

            $firstDetail = $details->first();

            return (object)[
                'name' => $firstDetail->item->nama ?? 'Item Tidak Ditemukan',
                'total_quantity' => $details->sum('quantity'),
                'total_revenue' => $details->sum(function ($detail) {
                    return $detail->quantity * $detail->price;
                }),
            ];
        })
        ->values();

    $pdf = \PDF::loadView(
        'laporantransaksi.cetak_transaksi',
        compact('itemsSummary', 'tanggal')
    );

    return $pdf->stream("laporan-transaksi-{$tanggal}.pdf");
}
}