<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopupApiController extends Controller
{
    public function index()
    {
        $topups = Topup::with('konsumen')->latest()->paginate(10);
        return response()->json($topups);
    }

    public function confirm($id)
    {
        $topup = Topup::findOrFail($id);
        $topup->status = 'confirmed';
        $topup->save();

        return response()->json(['message' => 'Topup berhasil dikonfirmasi']);
    }

    public function verifikasiQR(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        return response()->json($user);
    }

    public function histori()
    {
        $topups = DB::table('topups')
            ->join('users', 'topups.user_id', '=', 'users.id')
            ->select('topups.*', 'users.name as nama_user')
            ->orderByDesc('topups.created_at')
            ->paginate(15);

        return response()->json($topups);
    }

    public function topupManual(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->saldo += $request->jumlah;
        $user->save();

        Topup::create([
            'user_id' => $user->id,
            'jumlah' => $request->jumlah,
            'status' => 'manual',
        ]);

        return response()->json(['message' => 'Topup manual berhasil ditambahkan.']);
    }
}
