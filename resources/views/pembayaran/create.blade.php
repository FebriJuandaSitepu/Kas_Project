@extends('layouts.main')

@section('title', 'Tambah Pembayaran')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Tambah Pembayaran Manual</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="user_id" class="form-label">Pilih User</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tipe" class="form-label">Tipe Transaksi</label>
            <select name="tipe" class="form-select" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="metode" class="form-label">Metode Pembayaran</label>
            <input type="text" name="metode" class="form-control" placeholder="Contoh: Transfer Bank" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,application/pdf">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
