<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Konsumen;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPengguna = Konsumen::count();
        $totalPembayaran = Pembayaran::count();
        $totalPemasukan = Pembayaran::where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Pembayaran::where('tipe', 'pengeluaran')->sum('jumlah');

        $transaksiTerakhir = Pembayaran::with('konsumen')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.index', compact(
    'totalPengguna',
    'totalPembayaran',
    'totalPemasukan',
    'totalPengeluaran',
    'transaksiTerakhir'
));

    }
}
