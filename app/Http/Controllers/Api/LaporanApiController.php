<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class LaporanApiController extends Controller
{
    /**
     * Menampilkan rekap total transaksi per bulan untuk tiap user.
     *
     * @return JsonResponse
     */
    public function rekapTransaksi(): JsonResponse
    {
        // Ambil semua user
        $users = User::select('id', 'name')->get();

        // Ambil rekap jumlah transaksi berdasarkan bulan
        $rekap = DB::table('pembayaran')
            ->select(
                'user_id',
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('user_id', DB::raw('MONTH(created_at)'))
            ->get();

        // Format hasil rekap ke dalam bentuk array per user
        $data = $users->map(function ($user) use ($rekap) {
            $userRekap = $rekap->where('user_id', $user->id);

            $bulan = [];
            for ($i = 1; $i <= 12; $i++) {
                $bulan[$i] = (float) ($userRekap->firstWhere('bulan', $i)->total ?? 0);
            }

            return [
                'user' => $user->name,
                'transaksi_per_bulan' => $bulan,
            ];
        });

        // Kembalikan sebagai JSON
        return response()->json([
            'status' => true,
            'message' => 'Rekap transaksi per pengguna',
            'data' => $data
        ]);
    }
}
