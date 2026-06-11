<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrisSetting;

class QrisController extends Controller
{
    public function active()
    {
        $qris = QrisSetting::where('is_active', true)->first();

        if (!$qris) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada QRIS aktif'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $qris->id,
                'foto_url' => asset('storage/' . $qris->foto),
                'nama_merchant' => $qris->nama_merchant,
                'keterangan' => $qris->keterangan,
            ]
        ]);
    }
}
