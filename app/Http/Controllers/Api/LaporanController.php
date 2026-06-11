<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\Transaction;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function barangMasuk(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $query = Stock::with(['ingredient', 'category']);

        if ($dari) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data->map(function ($stock) {
                return [
                    'id' => $stock->id,
                    'kode' => $stock->ingredient->kode ?? '-',
                    'nama' => $stock->ingredient->nama ?? '-',
                    'kategori' => $stock->category->nama ?? '-',
                    'jumlah' => $stock->jumlah,
                    'jumlah_awal' => $stock->jumlah_awal,
                    'satuan' => $stock->satuan,
                    'tanggal' => $stock->tanggal ? Carbon::parse($stock->tanggal)->format('Y-m-d') : '-',
                ];
            })
        ]);
    }

    public function barangKeluar(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $query = StockHistory::with(['ingredient', 'stock']);

        if ($dari) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data->map(function ($history) {
                return [
                    'id' => $history->id,
                    'kode' => $history->ingredient->kode ?? '-',
                    'nama' => $history->ingredient->nama ?? '-',
                    'jumlah' => $history->jumlah,
                    'satuan' => $history->unit ?? '-',
                    'keterangan' => $history->keterangan,
                    'tanggal' => $history->tanggal ? Carbon::parse($history->tanggal)->format('Y-m-d') : '-',
                ];
            })
        ]);
    }

    public function transaksi(Request $request)
    {
        $tanggal = $request->query('tanggal', Carbon::today()->format('Y-m-d'));

        $transactions = Transaction::with(['details.item'])
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTunai = $transactions->where('payment_method', 'cash')->sum('total');
        $totalQris = $transactions->where('payment_method', 'qris')->sum('total');
        $grandTotal = $transactions->sum('total');

        return response()->json([
            'success' => true,
            'tanggal' => $tanggal,
            'summary' => [
                'total_tunai' => $totalTunai,
                'total_qris' => $totalQris,
                'grand_total' => $grandTotal,
                'jumlah_transaksi' => $transactions->count(),
            ],
            'data' => $transactions->map(function ($trx) {
                return [
                    'id' => $trx->id,
                    'total' => $trx->total,
                    'payment_method' => $trx->payment_method,
                    'waktu' => $trx->created_at->format('H:i:s'),
                    'items' => $trx->details->map(function ($d) {
                        return [
                            'nama' => $d->item->nama ?? '-',
                            'quantity' => $d->quantity,
                            'price' => $d->price,
                            'total_price' => $d->total_price,
                        ];
                    })
                ];
            })
        ]);
    }
}
