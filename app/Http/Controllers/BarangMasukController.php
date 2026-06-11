<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        // Menampilkan data barang masuk dengan relasi ingredient dan category
        $stocks = Stock::with(['ingredient', 'category'])->paginate(10);
        return view('barangmasuk.index', compact('stocks'));
    }

    public function create()
    {
        // Menampilkan form untuk menambah barang masuk
        $ingredients = Ingredient::all();  // Mengambil data ingredient
        $categories = Category::all();  // Mengambil data kategori
        return view('barangmasuk.create', compact('ingredients', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'category_id' => 'required|exists:categories,id',
            'jumlah' => 'required|numeric|min:1',
            'satuan' => 'required|string',
            'tanggal' => 'required|date'
        ]);

        try {
            // Cek apakah sudah ada stok untuk bahan dan kategori yang sama
            $existingStock = Stock::where('ingredient_id', $validated['ingredient_id'])
                ->where('category_id', $validated['category_id'])
                ->first();

            if ($existingStock) {
                // Jika sudah ada, update jumlahnya
                $existingStock->jumlah += $validated['jumlah'];
                $existingStock->jumlah_awal = $validated['jumlah'];
                $existingStock->tanggal = $validated['tanggal'];
                $existingStock->save();
            } else {
                // Jika belum ada, buat stok baru
                Stock::create([
                    'ingredient_id' => $validated['ingredient_id'],
                    'category_id' => $validated['category_id'],
                    'jumlah' => $validated['jumlah'],
                    'jumlah_awal' => $validated['jumlah'],
                    'satuan' => $validated['satuan'],
                    'tanggal' => $validated['tanggal']
                ]);
            }

            return redirect()->route('barangmasuk.index')
                ->with('success', 'Barang masuk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah stok')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            // Mengambil data stock berdasarkan ID dengan relasi
            $stock = Stock::with(['ingredient', 'category'])->findOrFail($id);
            $ingredients = Ingredient::all();
            $categories = Category::all();

            return view('barangmasuk.edit', compact('stock', 'ingredients', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error editing stock: ' . $e->getMessage());
            return redirect()->route('barangmasuk.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'ingredient_id' => 'required|exists:ingredients,id',
                'category_id' => 'required|exists:categories,id',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|string',
                'tanggal' => 'required|date'
            ]);

            // Update data
            $stock = Stock::findOrFail($id);
            $validated['jumlah_awal'] = $validated['jumlah'];
            $stock->update($validated);

            return redirect()->route('barangmasuk.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating stock: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    public function destroy($id)
    {
        try {
            // Menghapus data barang masuk
            $stock = Stock::findOrFail($id);
            
            // Hapus stock history terkait jika ada
            $stock->histories()->delete();
            
            // Hapus stock
            $stock->delete();

            return redirect()->route('barangmasuk.index')
                ->with('success', 'Barang masuk berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error deleting stock: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    // ===========================
// API MOBILE BARANG MASUK
// ===========================

// LIST
public function apiIndex()
{
    $stocks = Stock::with([
        'ingredient',
        'category',
        'histories'
    ])
    ->orderBy('id', 'asc')
    ->get();

    $data = $stocks->map(function ($stock) {
        return [
            'id' => $stock->id,
            'tanggal' => $stock->tanggal,
            'satuan' => $stock->satuan,

            'jumlah_awal' => $stock->jumlah_awal,
            'jumlah' => $stock->jumlah,

            'barang_keluar' =>
                $stock->histories->sum('jumlah'),

            'ingredient' =>
                $stock->ingredient,

            'category' =>
                $stock->category,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}

// DETAIL
public function apiShow($id)
{
    $stock = Stock::with([
        'ingredient',
        'category',
        'histories'
    ])->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => $stock
    ]);
}

// TAMBAH
public function apiStore(Request $request)
{
    $request->validate([
        'ingredient_id' => 'required|exists:ingredients,id',
        'category_id' => 'required|exists:categories,id',
        'jumlah' => 'required|numeric|min:1',
        'tanggal' => 'required|date',
        'satuan' => 'required|string',
    ]);

    $stock = Stock::where(
        'ingredient_id',
        $request->ingredient_id
    )->first();

    if ($stock) {

        $stock->update([
            'jumlah_awal' => $request->jumlah,
            'jumlah' => $stock->jumlah + $request->jumlah,
            'tanggal' => $request->tanggal,
            'satuan' => $request->satuan,
            'category_id' => $request->category_id,
        ]);

    } else {

        $stock = Stock::create([
            'ingredient_id' => $request->ingredient_id,
            'category_id' => $request->category_id,
            'jumlah' => $request->jumlah,
            'jumlah_awal' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'satuan' => $request->satuan,
        ]);
    }

    $ingredient = Ingredient::find(
        $request->ingredient_id
    );

    $ingredient->increment(
        'stok',
        $request->jumlah
    );

    return response()->json([
        'success' => true,
        'message' => 'Barang masuk berhasil disimpan',
        'data' => $stock
    ]);
}

// UPDATE
public function apiUpdate(Request $request, $id)
{
    $stock = Stock::findOrFail($id);

    $request->validate([
        'ingredient_id' => 'required|exists:ingredients,id',
        'category_id' => 'required|exists:categories,id',
        'jumlah' => 'required|numeric|min:1',
        'tanggal' => 'required|date',
        'satuan' => 'required|string',
    ]);

    $stock->update([
        'ingredient_id' => $request->ingredient_id,
        'category_id' => $request->category_id,
        'jumlah' => $request->jumlah,
        'tanggal' => $request->tanggal,
        'satuan' => $request->satuan,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Barang masuk berhasil diperbarui'
    ]);
}

// HAPUS
public function apiDestroy($id)
{
    $stock = Stock::findOrFail($id);

    $stock->delete();

    return response()->json([
        'success' => true,
        'message' => 'Barang masuk berhasil dihapus'
    ]);
}
}
