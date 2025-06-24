@extends('layouts.main')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Pengeluaran Kas</h1>

    <form action="{{ route('pembayaran.store') }}" method="POST">
        @csrf

        {{-- Pilih User --}}
        <div class="mb-3">
            <label for="user_id" class="form-label">Pengguna</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">-- Pilih Pengguna --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Hidden tipe: pengeluaran --}}
        <input type="hidden" name="tipe" value="pengeluaran">

        {{-- Jumlah --}}
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required min="0">
        </div>

        {{-- Metode --}}
        <div class="mb-3">
            <label for="metode" class="form-label">Metode Pembayaran</label>
            <input type="text" name="metode" id="metode" class="form-control" required>
        </div>

        {{-- Tanggal --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-danger">Simpan Pengeluaran</button>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
