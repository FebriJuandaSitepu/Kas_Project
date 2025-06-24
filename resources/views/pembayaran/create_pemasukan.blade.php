@extends('layouts.main') <!-- Sesuaikan jika layout kamu bernama lain -->

@section('content')
<div class="container">
    <h3>Form Tambah Pemasukan</h3>

    {{-- Tampilkan error validasi jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Simpan tipe tersembunyi sebagai "pemasukan" --}}
        <input type="hidden" name="tipe" value="pemasukan">

        <div class="mb-3">
            <label for="user_id" class="form-label">Pilih User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">-- Pilih Pengguna --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Pemasukan</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="metode" class="form-label">Metode Pembayaran</label>
            <input type="text" name="metode" id="metode" class="form-control" placeholder="Transfer / QR / dll" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-3">
    <label for="bukti" class="form-label">Bukti Pembayaran (Optional)</label>
    <input type="file" name="bukti" id="bukti" class="form-control">

    @if(isset($item) && $item->bukti)
    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="btn btn-sm btn-primary mt-2">Lihat Bukti</a>
    <p class="text-muted mt-2">Tidak ada bukti</p>
@endif
</div>


        <button type="submit" class="btn btn-success">Simpan Pemasukan</button>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
