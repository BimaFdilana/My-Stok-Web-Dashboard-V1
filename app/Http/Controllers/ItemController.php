<?php

namespace App\Http\Controllers;


use App\Models\Item;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class ItemController extends Controller
{
    // Menampilkan semua item
    public function index()
    {
       $items = Item::with('ingredients')->get();
        $categories = Category::all();
        return view('produk.index', compact('items', 'categories'));
    }

    // Menampilkan form untuk membuat item baru
    public function create(Request $request)
    {
        // Mengambil kategori berdasarkan ID dari query parameter
        $categoryId = $request->query('category');
        $category = Category::findOrFail($categoryId);
        $ingredients = Ingredient::all();
        return view('produk.create', compact('category', 'ingredients'));
    }

    // Menyimpan item baru dan ingredients
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|unique:items,kode|max:255',
                'nama' => 'required|max:255',
                'harga' => 'required|integer',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'kategori_id' => 'required|exists:categories,id',
                'ingredients.*.kode' => 'required|max:255',
                'ingredients.*.nama' => 'required|max:255',
                'ingredients.*.stok' => 'required|integer|min:1',
                'ingredients.*.satuan' => 'required|max:255',
            ]);

            \Log::info('Validated data:', $validated);

            $item = Item::create([
                'kode' => $validated['kode'],
                'nama' => $validated['nama'],
                'harga' => $validated['harga'],
                'kategori_id' => $validated['kategori_id'],
                'foto' => $request->hasFile('foto')
                    ? $request->file('foto')->store('item_images', 'public')
                    : null,
            ]);

            \Log::info('Created item:', $item->toArray());

            // Menambahkan ingredients ke dalam pivot table jika ada
            if (isset($validated['ingredients'])) {
                foreach ($validated['ingredients'] as $ingredient) {
                    // Cek apakah ingredient sudah ada dalam database, jika tidak buat baru
                    $ingredientModel = Ingredient::firstOrCreate(
                        ['kode' => $ingredient['kode']],
                        [
                            'nama' => $ingredient['nama'],
                            'stok' => $ingredient['stok'],
                            'satuan' => $ingredient['satuan'],
                            'kategori_id' => $validated['kategori_id']
                        ]
                    );

                    // Menambahkan ingredient ke pivot table dengan jumlah dan satuan
                    $item->ingredients()->attach($ingredientModel->id, [
                        'jumlah' => $ingredient['stok'],
                        'satuan' => $ingredient['satuan']
                    ]);
                }
            }

            // Redirect ke halaman index produk dengan pesan sukses
            return redirect()->route('produks.index')->with('success', 'Produk dan bahan baku berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating item: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage());
        }
    }

    // Menghapus item beserta foto jika ada
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // Hapus relasi ingredients
        $item->ingredients()->detach();

        // Hapus foto dari storage jika ada
        if ($item->foto) {
            Storage::delete('public/item_images/' . $item->foto);
        }

        // Hapus item dari database
        $item->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }


    // Menampilkan detail item beserta ingredients
    public function show($id)
    {
        // Ambil data produk berdasarkan ID, termasuk ingredients
        $item = Item::with('ingredients')->find($id);

        // Cek apakah produk ditemukan
        if (!$item) {
            return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan.');
        }

        return view('produk.detail', compact('item'));
    }

    // Menampilkan form edit produk
public function edit($id)
{
    $item = Item::with('ingredients')->findOrFail($id);
    $category = Category::find($item->kategori_id);

    return view('produk.edit', compact('item', 'category'));
}

// Update produk
public function update(Request $request, $id)
{
    $item = Item::findOrFail($id);

$validated = $request->validate([
    'kode' => 'required|max:255|unique:items,kode,' . $id,
    'nama' => 'required|max:255',
    'harga' => 'required|integer',
    'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'ingredients.*.kode' => 'required|max:255',
    'ingredients.*.nama' => 'required|max:255',
    'ingredients.*.stok' => 'required|integer|min:1',
    'ingredients.*.satuan' => 'required|max:255',
]);


$item->update([
    'kode' => $validated['kode'],
    'nama' => $validated['nama'],
    'harga' => $validated['harga'],
    'foto' => $item->foto,
]);

// dd([
//     'berhasil_update' => true,
//     'item' => $item->fresh()->toArray()
// ]);

// $item->update([
//     'kode' => $validated['kode'],
//     'nama' => $validated['nama'],
//     'harga' => $validated['harga'],
//     'foto' => $item->foto,
// ]);

    // Hapus relasi ingredient lama
    $item->ingredients()->detach();

    // Simpan ingredient baru
    foreach ($validated['ingredients'] as $ingredient) {

    $ingredientModel = Ingredient::firstOrCreate(
        ['kode' => $ingredient['kode']],
        [
            'nama' => $ingredient['nama'],
            'stok' => 0,
            'satuan' => $ingredient['satuan'],
            'kategori_id' => $item->kategori_id
        ]
    );

    $item->ingredients()->attach(
        $ingredientModel->id,
        [
            'jumlah' => $ingredient['stok'],
            'satuan' => $ingredient['satuan']
        ]
    );
}

// dd(
//     $item->ingredients()
//         ->withPivot('jumlah','satuan')
//         ->get()
//         ->toArray()
// );

    return redirect()
        ->route('produks.index')
        ->with('success', 'Produk berhasil diperbarui');
}

// ======================
// API MOBILE
// ======================

// Ambil semua produk
public function apiIndex()
{
    $items = Item::with('ingredients')->get();

    return response()->json([
        'success' => true,
        'data' => $items
    ]);
}

// Detail produk
public function apiShow($id)
{
    $item = Item::with('ingredients')->find($id);

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $item
    ]);
}

public function apiStore(Request $request)
{
    $validated = $request->validate([
        'kode' => 'required|unique:items,kode',
        'nama' => 'required',
        'harga' => 'required|integer',
        'kategori_id' => 'required|exists:categories,id',
        'foto' => 'nullable|image',

        'ingredients.*.kode' => 'required',
        'ingredients.*.nama' => 'required',
        'ingredients.*.stok' => 'required|integer|min:1',
        'ingredients.*.satuan' => 'required',
    ]);

    $foto = null;

    if ($request->hasFile('foto')) {
        $foto = $request->file('foto')
            ->store('item_images', 'public');
    }

    $item = Item::create([
        'kode' => $validated['kode'],
        'nama' => $validated['nama'],
        'harga' => $validated['harga'],
        'kategori_id' => $validated['kategori_id'],
        'foto' => $foto,
    ]);

    if (isset($validated['ingredients'])) {

        foreach ($validated['ingredients'] as $ingredient) {

                    $ingredientModel = Ingredient::firstOrCreate(
                ['kode' => $ingredient['kode']],
                [
                    'nama' => $ingredient['nama'],
                    'stok' => $ingredient['stok'],
                    'satuan' => $ingredient['satuan'],
                    'kategori_id' => $validated['kategori_id']
                ]
            );

            $item->ingredients()->attach(
                $ingredientModel->id,
                [
                    'jumlah' => $ingredient['stok'],
                    'satuan' => $ingredient['satuan']
                ]
            );
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Produk berhasil ditambahkan',
        'data' => $item->load('ingredients')
    ]);
}

public function apiDestroy($id)
{
    $item = Item::find($id);

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    // hapus relasi ingredient
    $item->ingredients()->detach();

    // hapus foto
    if ($item->foto) {

        if (Storage::disk('public')->exists($item->foto)) {
            Storage::disk('public')->delete($item->foto);
        }
    }

    $item->delete();

    return response()->json([
        'success' => true,
        'message' => 'Produk berhasil dihapus'
    ]);
}

public function apiUpdate(Request $request, $id)
{
    \Log::info('API UPDATE MASUK');
    \Log::info($request->all());

    $item = Item::findOrFail($id);

    $validated = $request->validate([
        'kode' => 'required|max:255|unique:items,kode,' . $id,
        'nama' => 'required|max:255',
        'harga' => 'required|integer',
        'foto' => 'nullable|image',
        'ingredients.*.kode' => 'required',
        'ingredients.*.nama' => 'required',
        'ingredients.*.stok' => 'required|integer|min:1',
        'ingredients.*.satuan' => 'required',
    ]);

    if ($request->hasFile('foto')) {

        if (
            $item->foto &&
            Storage::disk('public')->exists($item->foto)
        ) {
            Storage::disk('public')->delete($item->foto);
        }

        $item->foto = $request
            ->file('foto')
            ->store('item_images', 'public');
    }

    $item->update([
        'kode' => $validated['kode'],
        'nama' => $validated['nama'],
        'harga' => $validated['harga'],
        'foto' => $item->foto,
    ]);

    $item->ingredients()->detach();

    foreach ($validated['ingredients'] as $ingredient) {

      $ingredientModel = Ingredient::firstOrCreate(
    ['kode' => $ingredient['kode']],
    [
        'nama' => $ingredient['nama'],
        'stok' => 0,
        'satuan' => $ingredient['satuan'],
        'kategori_id' => $item->kategori_id
    ]
);

        $item->ingredients()->attach(
            $ingredientModel->id,
            [
                'jumlah' => $ingredient['stok'],
                'satuan' => $ingredient['satuan']
            ]
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Produk berhasil diperbarui'
    ]);
}
}