<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Method to display the dashboard page
    public function dashboard()
    {
        // Mengambil data stok dengan relasi ke ingredient dan kategori
    $stocks = Stock::with(['ingredient', 'category'])->paginate(10);

    // Mengirim data ke view
    return view('auth.dashboard', compact('stocks'));
    }

    // Method to display the list of stocks with pagination
    public function index(Request $request)
{
    $stocks = Stock::with([
        'ingredient',
        'category',
        'histories'
    ])->paginate(10);

    return view('stocks.index', compact('stocks'));
}

    // Method to view a specific stock's details
    public function show($id)
    {
        $stock = Stock::findOrFail($id); // Find stock by ID
        return view('stocks.show', compact('stock')); // Return stock details view
    }

    public function create()
    {
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return view('stocks.create', compact('ingredients', 'categories'));
    }

    // Method to store a new stock (barang masuk)
    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'category_id' => 'required|exists:categories,id',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required|date',
            'satuan' => 'required|string',
        ]);

        $stock = new Stock();
        $stock->ingredient_id = $request->ingredient_id;
        $stock->category_id = $request->category_id;
        $stock->jumlah = $request->jumlah;
        $stock->jumlah_awal = $request->jumlah;
        $stock->tanggal = $request->tanggal;
        $stock->satuan = $request->satuan;
        $stock->save();

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil ditambahkan');
    }

    // Method to edit stock
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return view('stocks.edit', compact('stock', 'ingredients', 'categories'));
    }

    // Method to update stock
    public function update(Request $request, $id)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'category_id' => 'required|exists:categories,id',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required|date',
            'satuan' => 'required|string',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->only(['ingredient_id', 'category_id', 'jumlah', 'tanggal', 'satuan']));

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui');
    }

    // Method to delete stock
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil dihapus');
    }

    public function barangMasukIndex()
    {
        $ingredients = Ingredient::all(); // Ambil semua data dari tabel ingredients
        return view('barangmasuk.index', compact('ingredients')); // Kirim data ke view
    }

    public function barangMasukStore(Request $request)
{
    $request->validate([
        'ingredient_id' => 'required|exists:ingredients,id',
        'category_id' => 'required|exists:categories,id',
        'jumlah' => 'required|numeric|min:1',
        'tanggal' => 'required|date',
        'satuan' => 'required|string',
    ]);

    Stock::create([
        'ingredient_id' => $request->ingredient_id,
        'category_id' => $request->category_id,
        'jumlah' => $request->jumlah,
        'jumlah_awal' => $request->jumlah,
        'tanggal' => $request->tanggal,
        'satuan' => $request->satuan,
    ]);

    return redirect()->route('barangmasuk.index')
        ->with('success', 'Barang masuk berhasil disimpan');
}

        public function laporan(Request $request)
        {
            // Validasi input tanggal
            $request->validate([
                'tanggal' => 'required|date',
            ]);

            // Ambil data barang masuk berdasarkan tanggal yang dimasukkan oleh user
            $tanggal = $request->tanggal;

            $stocks = Stock::whereDate('tanggal', $tanggal)
                ->with('ingredient')
                ->get();

            // Jika ingin membuat laporan dalam bentuk PDF atau file, Anda bisa menggunakan library seperti DomPDF
            // Misalnya menggunakan dompdf:
            // $pdf = \PDF::loadView('laporan_barang_masuk', compact('stocks'));
            // return $pdf->download('laporan_barang_masuk_' . $tanggal . '.pdf');

            return view('barangmasuk.laporan', compact('stocks', 'tanggal'));
        }
}