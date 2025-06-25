<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Konsumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('konsumen');

        if ($request->filled('search')) {
            $query->whereHas('konsumen', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pembayaran.index', compact('pembayaran'));
    }

    // Form create dengan parameter tipe opsional
    public function create($tipe = null)
    {
        $konsumens = Konsumen::all();

        // Validasi tipe jika diberikan
        if ($tipe && !in_array($tipe, ['pemasukan', 'pengeluaran'])) {
            abort(404, 'Tipe pembayaran tidak valid.');
        }

        return view('pembayaran.create', compact('konsumens', 'tipe'));
    }

    // Simpan pembayaran baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'konsumen_id' => 'required|exists:konsumens,no_identitas',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'metode' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'bukti' => 'nullable|image|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_pembayaran', 'public');
        }

        Pembayaran::create([
            'konsumen_id' => $validated['konsumen_id'],
            'tipe' => $validated['tipe'],
            'jumlah' => $validated['jumlah'],
            'metode' => $validated['metode'],
            'tanggal' => $validated['tanggal'],
            'status' => 'valid',
            'bukti' => $buktiPath,
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil disimpan.');
    }

    // Form edit pembayaran
    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $konsumens = Konsumen::all();

        return view('pembayaran.edit', compact('pembayaran', 'konsumens'));
    }

    // Update pembayaran
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'metode' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'bukti' => 'nullable|image|max:2048',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($request->hasFile('bukti')) {
            if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
                Storage::disk('public')->delete($pembayaran->bukti);
            }

            $buktiBaru = $request->file('bukti')->store('bukti_pembayaran', 'public');
            $pembayaran->bukti = $buktiBaru;
        }

        $pembayaran->update([
            'jumlah' => $validated['jumlah'],
            'metode' => $validated['metode'],
            'tanggal' => $validated['tanggal'],
            'tipe' => $validated['tipe'],
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    // Update status pembayaran
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:valid,pending,ditolak'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->status = $request->status;
        $pembayaran->save();

        return redirect()->route('pembayaran.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    // Hapus pembayaran
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
            Storage::disk('public')->delete($pembayaran->bukti);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
