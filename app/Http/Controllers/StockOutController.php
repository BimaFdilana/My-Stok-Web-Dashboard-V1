<?php
namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockHistories = StockHistory::with(['ingredient', 'stock', 'stock.category'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('barangkeluar.index', compact('stockHistories'));
    }
}
