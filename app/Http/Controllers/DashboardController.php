<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count(); // Ganti nama variabel agar sama dengan compact()

        $totalPembayaran = Pembayaran::count();

        $totalPemasukan = Pembayaran::where('tipe', 'Pemasukan')
                                    ->where('status', 'Valid')
                                    ->sum('jumlah');

        $totalPengeluaran = Pembayaran::where('tipe', 'Pengeluaran')
                                      ->where('status', 'Valid')
                                      ->sum('jumlah');

        $transaksiTerakhir = Pembayaran::with('user')
                                       ->latest()
                                       ->take(5)
                                       ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPembayaran',
            'totalPemasukan',
            'totalPengeluaran',
            'transaksiTerakhir'
        ));
    }
}
