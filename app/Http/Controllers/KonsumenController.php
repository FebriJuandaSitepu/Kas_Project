<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KonsumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan semua user yang bukan admin
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('konsumen.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('konsumen.form', [
            'user' => new User()
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make('password123'), // default password
        ]);

        return redirect()->route('konsumen.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('konsumen.form', [
            'user' => $user
        ]);
    }

    // Update data user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('konsumen.index')->with('success', 'User berhasil diupdate.');
    }

    // Hapus user
   public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    return redirect()->route('konsumen.index')->with('success', 'User berhasil dihapus.');
}


    // Reset password user ke default
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('password123');
        $user->save();

        return back()->with('success', 'Password berhasil direset ke default.');
    }
}
