<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $users = User::all();
        return view('pembayaran.create', compact('users'));
    }

    public function createPemasukan()
    {
        $users = User::all();
        return view('pembayaran.create_pemasukan', compact('users'));
    }

    public function createPengeluaran()
    {
        $users = User::all();
        return view('pembayaran.create_pengeluaran', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'user_id' => $validated['user_id'],
            'tipe' => $validated['tipe'],
            'jumlah' => $validated['jumlah'],
            'metode' => $validated['metode'],
            'tanggal' => $validated['tanggal'],
            'status' => 'valid',
            'bukti' => $buktiPath,
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil disimpan.');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('pembayaran.edit', compact('pembayaran'));
    }

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

        // Upload bukti baru (jika ada)
        if ($request->hasFile('bukti')) {
            // Hapus file lama jika ada
            if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
                Storage::disk('public')->delete($pembayaran->bukti);
            }

            $buktiBaru = $request->file('bukti')->store('bukti_pembayaran', 'public');
            $pembayaran->bukti = $buktiBaru;
        }

        // Update data lainnya
        $pembayaran->update([
            'jumlah' => $validated['jumlah'],
            'metode' => $validated['metode'],
            'tanggal' => $validated['tanggal'],
            'tipe' => $validated['tipe'],
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

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

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        // Hapus file bukti jika ada
        if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
            Storage::disk('public')->delete($pembayaran->bukti);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
