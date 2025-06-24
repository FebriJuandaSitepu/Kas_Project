<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konsumen;
use Illuminate\Http\Request;

class KonsumenApiController extends Controller
{
    public function index()
    {
        return response()->json(Konsumen::all());
    }

    public function store(Request $request)
    {
        $data = Konsumen::create($request->all());
        return response()->json($data);
    }

    public function show($id)
    {
        return response()->json(Konsumen::find($id));
    }

    public function update(Request $request, $id)
    {
        $data = Konsumen::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        Konsumen::destroy($id);
        return response()->json(['message' => 'Konsumen dihapus']);
    }

    public function resetPassword($id)
    {
        // Tambahkan logika reset password sesuai kebutuhan
        return response()->json(['message' => 'Password berhasil direset']);
    }
}
