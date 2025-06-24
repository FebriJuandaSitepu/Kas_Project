<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranApiController extends Controller
{
    public function index() {
        return response()->json(Pembayaran::all());
    }

    public function store(Request $request) {
        $data = Pembayaran::create($request->all());
        return response()->json($data);
    }

    public function update(Request $request, $id) {
        $data = Pembayaran::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id) {
        Pembayaran::destroy($id);
        return response()->json(['message' => 'Pembayaran dihapus']);
    }

    public function updateStatus($id) {
        // contoh update status
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->status = 'confirmed';
        $pembayaran->save();
        return response()->json(['message' => 'Status diperbarui']);
    }
}
