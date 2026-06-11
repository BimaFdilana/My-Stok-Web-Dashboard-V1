<?php
namespace App\Http\Controllers;

use App\Models\StockHistory;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockHistoryController extends Controller
{
    public function __construct()
    {
        // Set timezone untuk semua method di controller ini
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }

    // Menampilkan daftar barang keluar
    public function index(Request $request)
    {
        $categories = Category::all();

        // Update query untuk menggunakan relasi yang benar
        $stockHistories = StockHistory::with(['ingredient', 'stock.category'])
            ->when($request->category_id, function ($query) use ($request) {
                return $query->whereHas('stock', function($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('barangkeluar.index', compact('categories', 'stockHistories'));
    }

    // Menambah data barang keluar
    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'stock_id' => 'required|exists:stocks,id',
            'jumlah' => 'required|numeric|min:1',
            'unit' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Cek stok tersedia
        $stock = Stock::findOrFail($request->stock_id);
        if ($stock->jumlah < $request->jumlah) {
            return response()->json([
                'message' => 'Stok tidak mencukupi!'
            ], 422);
        }

        // Kurangi stok
        $stock->decrement('jumlah', $request->jumlah);

        // Buat history stok
        StockHistory::create([
            'ingredient_id' => $request->ingredient_id,
            'stock_id' => $request->stock_id,
            'jumlah' => $request->jumlah,
            'unit' => $request->unit,
            'tanggal' => Carbon::now('Asia/Jakarta'),
            'keterangan' => $request->keterangan ?? 'Barang Keluar',
        ]);

        return response()->json([
            'message' => 'Barang keluar berhasil dicatat!'
        ]);
    }

    // Menampilkan detail history barang keluar
    public function show($id)
    {
        $stockHistory = StockHistory::with(['ingredient', 'stock.category'])->findOrFail($id);
        return view('stock-histories.show', compact('stockHistory'));
    }

    // Mengupdate data history barang keluar
    public function update(Request $request, $id)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'kategori_id' => 'required|exists:categories,id',
            'jumlah' => 'required|numeric|min:1',
            'unit' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $stockHistory = StockHistory::findOrFail($id);
        $stockHistory->update($request->all());

        // Pastikan stok ingredient diupdate sesuai dengan perubahan jumlah barang keluar
        $ingredient = Ingredient::findOrFail($stockHistory->ingredient_id);
        $ingredient->stok -= ($request->jumlah - $stockHistory->jumlah); // Update stok berdasarkan perubahan jumlah
        $ingredient->save();

        return response()->json([
            'message' => 'History stok berhasil diperbarui!',
            'data' => $stockHistory
        ]);
    }

    // Menghapus data barang keluar
    public function destroy($id)
    {
        $stockHistory = StockHistory::findOrFail($id);

        // Kembalikan stok ingredient yang dikeluarkan
        $ingredient = Ingredient::findOrFail($stockHistory->ingredient_id);
        $ingredient->stok += $stockHistory->jumlah;
        $ingredient->save();

        $stockHistory->delete();

        return response()->json([
            'message' => 'History stok berhasil dihapus!'
        ]);
    }


    public function apiIndex()
{
    $data = StockHistory::with([
        'ingredient',
        'stock.category'
    ])
    ->orderBy('tanggal', 'desc')
    ->get();

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
}
