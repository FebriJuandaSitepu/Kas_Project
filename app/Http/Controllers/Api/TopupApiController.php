<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use Illuminate\Http\Request;

class TopupApiController extends Controller
{
    public function index()
    {
        return response()->json(Topup::all());
    }

    public function confirm($id)
    {
        $topup = Topup::findOrFail($id);
        $topup->status = 'confirmed';
        $topup->save();
        return response()->json(['message' => 'Topup dikonfirmasi']);
    }

    public function topupManual(Request $request)
    {
        // proses manual
        return response()->json(['message' => 'Topup manual berhasil']);
    }

    public function verifikasiQR(Request $request)
    {
        return response()->json(['message' => 'QR diverifikasi']);
    }

    public function histori()
    {
        return response()->json(['message' => 'Riwayat topup']);
    }
}
