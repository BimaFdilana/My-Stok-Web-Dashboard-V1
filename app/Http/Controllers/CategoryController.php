<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with(['ingredients', 'items'])->get(); // Tampilkan semua kategori beserta relasinya
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());

        return response()->json(['message' => 'Kategori berhasil ditambahkan!', 'data' => $category]);
    }

    public function show($id)
    {
        $category = Category::with(['ingredients', 'items'])->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json(['message' => 'Kategori berhasil diperbarui!', 'data' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus!']);
    }
}