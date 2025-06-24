<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil hanya user biasa (role = 'user')
        $users = User::select('id', 'name')
                     ->where('role', 'user')
                     ->get();

        // Ambil total transaksi per bulan per user dari tabel pembayaran
        $rekap = DB::table('pembayaran')
            ->select(
                'user_id',
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereIn('user_id', $users->pluck('id')) // pastikan hanya user biasa
            ->groupBy('user_id', DB::raw('MONTH(created_at)'))
            ->get()
            ->groupBy('user_id'); // dikelompokkan berdasarkan user_id

        return view('laporan.index', compact('users', 'rekap'));
    }
}
