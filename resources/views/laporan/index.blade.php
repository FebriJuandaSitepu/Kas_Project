@extends('layouts.main')

@section('title', 'Laporan Pengguna')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">📊 Laporan Transaksi Pengguna per Bulan</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle text-center">
            <thead class="table-dark sticky-top">
                <tr>
                    <th class="text-start">👤 Nama User</th>
                    @for ($i = 1; $i <= 12; $i++)
                        <th>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-start fw-semibold">{{ $user->name }}</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $total = 0;
                                if (isset($rekap[$user->id])) {
                                    $total = optional($rekap[$user->id]->firstWhere('bulan', $i))->total ?? 0;
                                }
                            @endphp
                            <td class="text-end">Rp{{ number_format($total, 0, ',', '.') }}</td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
