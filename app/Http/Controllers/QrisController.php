<?php

namespace App\Http\Controllers;

use App\Models\QrisSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrisController extends Controller
{
    public function index()
    {
        $qris = QrisSetting::first();
        return view('qris.index', compact('qris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_merchant' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $path = $request->file('foto')->store('qris', 'public');

        QrisSetting::where('is_active', true)->update(['is_active' => false]);

        QrisSetting::create([
            'foto' => $path,
            'nama_merchant' => $request->nama_merchant,
            'keterangan' => $request->keterangan,
            'is_active' => true,
        ]);

        return redirect()->route('qris.index')->with('success', 'QRIS berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $qris = QrisSetting::findOrFail($id);

        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_merchant' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $data = [
            'nama_merchant' => $request->nama_merchant,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('foto')) {
            if ($qris->foto) {
                Storage::disk('public')->delete($qris->foto);
            }
            $data['foto'] = $request->file('foto')->store('qris', 'public');
        }

        $qris->update($data);

        return redirect()->route('qris.index')->with('success', 'QRIS berhasil diperbarui');
    }

    public function destroy($id)
    {
        $qris = QrisSetting::findOrFail($id);

        if ($qris->foto) {
            Storage::disk('public')->delete($qris->foto);
        }

        $qris->delete();

        return redirect()->route('qris.index')->with('success', 'QRIS berhasil dihapus');
    }
}
