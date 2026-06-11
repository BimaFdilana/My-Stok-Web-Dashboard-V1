<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $items = Item::all();
        $transactions = Transaction::with('items')->get();

        return view('kasir.index', compact('items', 'transactions'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|json'
            ]);

            $items = json_decode($request->input('items'), true);

            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang dipilih'
                ], 400);
            }

            // Validasi stock untuk setiap item
                $processedItems = [];
                $insufficientIngredients = []; // Menampung bahan yang stoknya tidak mencukupi

                foreach ($items as $key => $item) {
                    $menuItem = Item::findOrFail($item['id']);
                    
                    // Cek stock untuk setiap bahan
                    foreach ($menuItem->ingredients as $ingredient) {
                        $requiredAmount = $ingredient->pivot->jumlah * $item['quantity'];
                        $stock = Stock::where('ingredient_id', $ingredient->id)->first();

                        if (!$stock || ($stock->jumlah - $requiredAmount) < 0) {
                            // Tambahkan bahan yang stoknya tidak mencukupi ke dalam array
                            $insufficientIngredients[] = $ingredient->nama;
                        }
                    }
                    
                    // Tambahkan data item yang sudah diproses
                    $processedItems[] = [
                        'id' => $item['id'],
                        'name' => $menuItem->nama,
                        'quantity' => $item['quantity'],
                        'price' => $menuItem->harga
                    ];
                }

                // Jika ada bahan yang stoknya tidak mencukupi
                if (!empty($insufficientIngredients)) {
                    $ingredientList = implode(', ', $insufficientIngredients);
                    return response()->json([
                        'success' => false,
                        'message' => "Maaf, stok berikut tidak mencukupi: {$ingredientList}. Silahkan perbarui stok terlebih dahulu."
                    ], 400);
                }


            // Jika validasi sukses, simpan ke session dengan data lengkap
            session(['pending_transaction' => [
                'items' => $processedItems,
                'total' => collect($processedItems)->sum(fn($item) => $item['price'] * $item['quantity'])
            ]]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Transaction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $transaction = Transaction::findOrFail($id);
        $item = Item::findOrFail($request->item_id);
        $ingredient = $item->ingredient;
        $stock = Stock::where('ingredient_id', $ingredient->id)->first();

        if (!$stock) {
            return response()->json(['message' => 'Stok tidak ditemukan!'], 404);
        }

        $oldQuantity = $transaction->quantity;
        $newQuantity = $request->quantity;
        $difference = $newQuantity - $oldQuantity;

        if ($difference > 0 && $stock->jumlah < $difference) {
            return response()->json(['message' => 'Stok tidak mencukupi!'], 400);
        }

        $transaction->update([
            'item_id' => $item->id,
            'quantity' => $newQuantity,
            'total_harga' => $item->harga * $newQuantity,
        ]);

        $stock->decrement('jumlah', $difference);
        $stock->save();

        return response()->json(['message' => 'Transaksi berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        foreach ($transaction->details as $detail) {
            $stock = Stock::where('ingredient_id', $detail->item->ingredient_id)->first();
            if ($stock) {
                $stock->increment('jumlah', $detail->quantity);
            }
        }

        $transaction->details()->delete();
        $transaction->delete();

        return response()->json(['message' => 'Transaksi berhasil dihapus!']);
    }

    public function summary()
    {
        if (!session()->has('pending_transaction')) {
            return redirect()->route('transactions.index');
        }

        $pendingTransaction = session('pending_transaction');
        $qris = \App\Models\QrisSetting::where('is_active', true)->first();
        return view('kasir.summary', compact('pendingTransaction', 'qris'));
    }

    public function process(Request $request)
    {
        try {
            \DB::beginTransaction();

            $pendingTransaction = session('pending_transaction');
            $tempPayment = session('temp_payment');

            if (!$pendingTransaction) {
                throw new \Exception('Tidak ada transaksi yang pending');
            }

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'total' => $pendingTransaction['total'],
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);

            // Simpan payment info ke session
            session(['payment_info' => [
                'transaction_id' => $transaction->id,
                'payment_method' => $request->payment_method ?? 'cash',
                'payment_amount' => $tempPayment['payment_amount'] ?? $pendingTransaction['total'],
                'change_amount' => $tempPayment['change_amount'] ?? 0
            ]]);

            foreach ($pendingTransaction['items'] as $itemData) {
                $item = Item::with('ingredients')->findOrFail($itemData['id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'total_price' => $itemData['price'] * $itemData['quantity'],
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);

                foreach ($item->ingredients as $ingredient) {
                    $requiredAmount = $ingredient->pivot->jumlah * $itemData['quantity'];
                    $stock = Stock::where('ingredient_id', $ingredient->id)->first();

                    if (!$stock) {
                        throw new \Exception("Stok untuk bahan '{$ingredient->nama}' tidak ditemukan.");
                    }

                    $stock->decrement('jumlah', $requiredAmount);

                    StockHistory::create([
                        'ingredient_id' => $ingredient->id,
                        'stock_id' => $stock->id,
                        'jumlah' => $requiredAmount,
                        'unit' => $ingredient->pivot->satuan,
                        'tanggal' => Carbon::now('Asia/Jakarta'),
                        'keterangan' => "Penggunaan untuk transaksi #" . $transaction->id
                    ]);
                }
            }

            \DB::commit();
            
            // Hapus session setelah redirect
            session()->forget(['pending_transaction', 'temp_payment']);

            // Langsung return view struk
            return redirect()->route('transactions.struk', $transaction->id);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Transaction error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function struk($id)
    {
        try {
            $transaction = Transaction::with(['details.item'])->findOrFail($id);
            $paymentInfo = session('payment_info');

            // Pastikan payment info sesuai dengan transaksi yang diminta
            if (!$paymentInfo || $paymentInfo['transaction_id'] != $id) {
                $paymentInfo = [
                    'transaction_id' => $transaction->id,
                    'payment_method' => 'cash',
                    'payment_amount' => $transaction->total,
                    'change_amount' => 0
                ];
            }

            return view('kasir.struk', compact('transaction', 'paymentInfo'));
        } catch (\Exception $e) {
            \Log::error('Struk error: ' . $e->getMessage());
            return redirect()->route('transactions.index')
                ->with('error', 'Gagal menampilkan struk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        return $this->struk($id);
    }

    public function savePayment(Request $request)
    {
        $request->validate([
            'payment_amount' => 'required|numeric',
            'change_amount' => 'required|numeric'
        ]);

        session([
            'temp_payment' => [
                'payment_amount' => $request->payment_amount,
                'change_amount' => $request->change_amount
            ]
        ]);

        return response()->json(['success' => true]);
    }

    public function validateStock(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = Item::with('ingredients')->findOrFail($request->item_id);
        
        foreach ($item->ingredients as $ingredient) {
            $requiredAmount = $ingredient->pivot->jumlah * $request->quantity;
            $stock = Stock::where('ingredient_id', $ingredient->id)->first();

            if (!$stock || ($stock->jumlah - $requiredAmount) < 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Maaf, stock {$ingredient->nama} tidak mencukupi. Silahkan perbarui stock terlebih dahulu."
                ], 400);
            }
        }

        return response()->json(['success' => true]);
    }
}
