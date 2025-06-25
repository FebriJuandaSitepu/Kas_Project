<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;

class LaporanController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with('user')->latest()->get();
        return view('laporan.index', compact('pembayarans'));
    }
}
