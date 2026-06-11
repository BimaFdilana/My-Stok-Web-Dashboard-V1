<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockHistory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : Carbon::today();
        
        $stockHistories = StockHistory::with(['stock.category', 'ingredient'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d');
            });

        return view('laporankeluar.index', [
            'stockHistories' => $stockHistories,
            'tanggal' => $tanggal,
            'laporanPerTanggal' => $stockHistories
        ]);
    }

    public function detail($tanggal)
    {
        $stockHistories = StockHistory::with(['stock.category', 'ingredient'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('laporankeluar.detail', compact('stockHistories', 'tanggal'));
    }

    public function cetakPDF($tanggal)
    {
        try {
            // Pastikan package dompdf terinstall
            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                throw new \Exception('DomPDF package not installed');
            }

            $parsedTanggal = Carbon::parse($tanggal)->format('Y-m-d');
            
            // Debug log
            \Log::info('Starting PDF generation for date: ' . $parsedTanggal);
            
            $stockHistories = StockHistory::with(['stock.category', 'ingredient'])
                ->whereDate('tanggal', $parsedTanggal)
                ->orderBy('tanggal', 'desc')
                ->get();

            // Debug log
            \Log::info('Found ' . $stockHistories->count() . ' records');
            
            // Cek apakah view exists
            if (!view()->exists('laporankeluar.cetak_barangkeluar')) {
                throw new \Exception('View file not found');
            }

            // Load view terlebih dahulu untuk debugging
            $view = view('laporankeluar.cetak_barangkeluar', [
                'stockHistories' => $stockHistories,
                'tanggal' => $parsedTanggal
            ])->render();

            // Debug log
            \Log::info('View rendered successfully');

            // Generate PDF
            $pdf = PDF::loadHTML($view);
            
            // Debug log
            \Log::info('PDF generated successfully');

            return $pdf->stream('laporan-barang-keluar-' . $parsedTanggal . '.pdf');

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return JSON response untuk Ajax request
            if (request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat mencetak PDF: ' . $e->getMessage());
        }
    }
}
