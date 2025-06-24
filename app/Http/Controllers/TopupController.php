<?php
namespace App\Http\Controllers;

use App\Models\Topup; // <-- TAMBAHIN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TopupController extends Controller
{
    public function index()
    {
        // Ambil semua data topup, sekalian data konsumennya (biar enteng)
        // Urutin dari yang paling baru
        $topups = Topup::with('konsumen')->latest()->paginate(10); // Paginate biar ga berat

        return view('topup', ['topups' => $topups]); // Kirim data ke view
    }
    // app/Http/Controllers/TopupController.php
public function verifikasiQR(Request $request)
{
    $userId = $request->input('user_id'); // Dikirim dari QR
    $user = User::find($userId);

    if (!$user) {
        return back()->with('error', 'Pengguna tidak ditemukan!');
    }

    return view('topup.verifikasi', compact('user'));
}
public function histori()
{
    $topups = DB::table('topups')
        ->join('users', 'topups.user_id', '=', 'users.id')
        ->select('topups.*', 'users.name as nama_user')
        ->orderByDesc('topups.created_at')
        ->paginate(15);

    return view('topup.histori', compact('topups'));
}
public function topupManual(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'jumlah' => 'required|numeric|min:1000',
    ]);

    // Tambah ke saldo user
    $user = User::findOrFail($request->user_id);
    $user->saldo += $request->jumlah;
    $user->save();

    // Simpan ke tabel topup (jika ada)
    DB::table('topups')->insert([
        'user_id' => $user->id,
        'jumlah' => $request->jumlah,
        'status' => 'manual',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Saldo berhasil ditambahkan.');
}

}