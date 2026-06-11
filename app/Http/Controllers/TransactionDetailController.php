<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TransactionDetailController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return TransactionDetail::with(['transaction', 'ingredient'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'jumlah' => 'required|integer',
        ]);

        $transactionDetail = TransactionDetail::create(array_merge(
            $request->all(),
            ['created_at' => Carbon::now('Asia/Jakarta')]
        ));

        return response()->json([
            'message' => 'Detail transaksi berhasil ditambahkan!', 
            'data' => $transactionDetail
        ]);
    }

    public function show($id)
    {
        $transactionDetail = TransactionDetail::with(['transaction', 'ingredient'])->findOrFail($id);
        return response()->json($transactionDetail);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'jumlah' => 'required|integer',
        ]);

        $transactionDetail = TransactionDetail::findOrFail($id);
        $transactionDetail->update($request->all());

        return response()->json(['message' => 'Detail transaksi berhasil diperbarui!', 'data' => $transactionDetail]);
    }

    public function destroy($id)
    {
        $transactionDetail = TransactionDetail::findOrFail($id);
        $transactionDetail->delete();

        return response()->json(['message' => 'Detail transaksi berhasil dihapus!']);
    }
}
