@extends('layouts.main')

@section('title', isset($user) ? 'Edit Konsumen' : 'Tambah Konsumen')

@section('content')
<div class="container mt-4">
    <h1>{{ isset($user) ? 'Edit Konsumen' : 'Tambah Konsumen' }}</h1>

    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah/Edit --}}
    <form action="{{ isset($user->id) ? route('konsumen.update', $user) : route('konsumen.store') }}" method="POST">
    @csrf
    @if(isset($user->id))
        @method('PUT')
    @endif


        <div class="mb-3">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" 
                   value="{{ old('name', $user->name ?? '') }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" 
                   value="{{ old('email', $user->email ?? '') }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
