<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirApiController extends Controller
{
    public function items()
    {
        $items = Item::with('category')->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'harga' => $item->harga,
                    'foto' => $item->foto ? asset('storage/' . $item->foto) : null,
                    'kategori' => $item->category ? $item->category->nama : null,
                ];
            })
        ]);
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'payment_method' => 'required|in:cash,qris',
                'payment_amount' => 'required|numeric',
                'items' => 'required|array'
            ]);

            $total = 0;

            foreach ($request->items as $cartItem) {
                $item = Item::with('ingredients')->findOrFail($cartItem['id']);

                foreach ($item->ingredients as $ingredient) {
                    $required = $ingredient->pivot->jumlah * $cartItem['quantity'];

                    $stock = Stock::where('ingredient_id', $ingredient->id)->first();

                    if (!$stock || $stock->jumlah < $required) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok ' . $ingredient->nama . ' tidak mencukupi'
                        ], 400);
                    }
                }

                $total += $item->harga * $cartItem['quantity'];
            }

            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'total' => $total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->items as $cartItem) {
                $item = Item::with('ingredients')->findOrFail($cartItem['id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $item->id,
                    'quantity' => $cartItem['quantity'],
                    'price' => $item->harga,
                    'total_price' => $item->harga * $cartItem['quantity']
                ]);

                foreach ($item->ingredients as $ingredient) {
                    $required = $ingredient->pivot->jumlah * $cartItem['quantity'];

                    $stock = Stock::where('ingredient_id', $ingredient->id)->first();
                    $stock->decrement('jumlah', $required);

                    StockHistory::create([
                        'ingredient_id' => $ingredient->id,
                        'stock_id' => $stock->id,
                        'jumlah' => $required,
                        'unit' => $ingredient->pivot->satuan,
                        'tanggal' => Carbon::now(),
                        'keterangan' => 'Penggunaan transaksi #' . $transaction->id
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_amount' => $request->payment_amount,
                'change' => $request->payment_amount - $total
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function receipt($id)
    {
        $transaction = Transaction::with(['details.item'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaction->id,
                'total' => $transaction->total,
                'payment_method' => $transaction->payment_method,
                'tanggal' => $transaction->created_at->format('Y-m-d H:i:s'),
                'items' => $transaction->details->map(function ($detail) {
                    return [
                        'nama' => $detail->item->nama,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'total_price' => $detail->total_price,
                    ];
                })
            ]
        ]);
    }
}
