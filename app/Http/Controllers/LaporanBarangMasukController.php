<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;

class LaporanBarangMasukController extends Controller
{
    /**
     * Tampilkan daftar laporan barang masuk per tanggal.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Debug tanggal
        \Log::info("Checking dates in stocks table");
        
        // Ubah query untuk mendapatkan data yang benar
        $laporanPerTanggal = Stock::with(['ingredient', 'category'])
            ->select(DB::raw('DATE(tanggal) as tanggal'), DB::raw('COUNT(*) as total_items'))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                // Debug setiap item
                \Log::info("Date found: " . $item->tanggal);
                return [
                    'tanggal' => $item->tanggal,
                    'total_items' => $item->total_items
                ];
            });

        // Debug hasil akhir
        \Log::info("Total dates found: " . $laporanPerTanggal->count());
        
        return view('laporanmasuk.index', [
            'laporanPerTanggal' => $laporanPerTanggal
        ]);
    }

    /**
     * Tampilkan detail laporan barang masuk berdasarkan tanggal.
     *
     * @param  string  $tanggal
     * @return \Illuminate\Http\Response
     */
    public function detail($tanggal)
    {
        try {
            $date = Carbon::parse($tanggal)->format('Y-m-d');
            
            // Debug untuk melihat query dan hasil
            \Log::info("Tanggal yang dicari: " . $date);
            
            $barangMasuk = Stock::with(['ingredient', 'category'])
                ->whereDate('tanggal', $date)
                ->get();

            \Log::info("Jumlah data ditemukan: " . $barangMasuk->count());

            // Transform data
            $transformedData = $barangMasuk->map(function ($item) {
                return [
                    'kode_barang' => $item->ingredient->kode ?? 'N/A',
                    'nama_barang' => $item->ingredient->nama ?? 'N/A',
                    'kategori' => $item->category->nama ?? 'N/A',
                    'barang_masuk' => $item->jumlah,
                    'satuan' => $item->satuan,
                    'tanggal' => $item->tanggal,
                ];
            });

            return view('laporanmasuk.detail', [
                'tanggal' => $tanggal,
                'barangMasuk' => $transformedData
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in detail: " . $e->getMessage());
            abort(404, 'Tanggal tidak valid.');
        }
    }

    /**
     * Cetak laporan barang masuk dalam format PDF.
     *
     * @param  string  $tanggal
     * @return \Illuminate\Http\Response
     */
    public function cetakPDF($tanggal)
    {
        try {
            $date = Carbon::parse($tanggal)->format('Y-m-d');
            
            // Ambil data barang masuk
            $barangMasuk = Stock::with(['ingredient', 'category'])
                ->whereDate('tanggal', $date)
                ->get()
                ->map(function ($item) {
                    return [
                        'kode_barang' => $item->ingredient->kode ?? 'N/A',
                        'nama_barang' => $item->ingredient->nama ?? 'N/A',
                        'kategori' => $item->category->nama ?? 'N/A',
                        'jumlah' => $item->jumlah,
                        'satuan' => $item->satuan,
                        'tanggal' => $item->tanggal,
                    ];
                });

            // Debug
            \Log::info("Generating PDF for date: " . $date);
            \Log::info("Data count: " . $barangMasuk->count());

            $pdf = PDF::loadView('laporanmasuk.cetak_barangmasuk', [
                'barangMasuk' => $barangMasuk,
                'tanggal' => $date
            ]);

            return $pdf->stream('laporan-barang-masuk-' . Carbon::parse($tanggal)->format('d-m-Y') . '.pdf');
        } catch (\Exception $e) {
            \Log::error("PDF Generation Error: " . $e->getMessage());
            abort(500, 'Error generating PDF');
        }
    }
}
