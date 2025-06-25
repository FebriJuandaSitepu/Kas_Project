<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konsumen;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanKonsumen()
    {
        // Ambil semua konsumen
        $konsumens = Konsumen::all();

        // Siapkan array rekap
        $rekap = [];

        // Untuk setiap konsumen, ambil total pembayaran per bulan
        foreach ($konsumens as $konsumen) {
    $rekap[$konsumen->no_identitas] = Pembayaran::selectRaw('MONTH(created_at) as bulan, SUM(jumlah) as total')
        ->where('konsumen_id', $konsumen->no_identitas)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();
}


        // Kirim ke view
        return view('laporan.index', compact('konsumens', 'rekap'));
    }
}
