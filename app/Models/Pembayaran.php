<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Nama tabel eksplisit (jika tidak pakai plural default)
    protected $table = 'pembayaran';

    // Kolom yang dapat diisi (mass assignment)
    protected $fillable = [
        'user_id',          // admin/operator yang memproses
        'konsumen_id',      // relasi ke Konsumen (no_identitas)
        'tipe',             // pemasukan / pengeluaran
        'jumlah',
        'metode',
        'tanggal',
        'status',
        'bukti',
        'bukti_pembayaran', // jika kamu memang menyimpan 2 bukti
        'deskripsi',
        'pemesanan_id',     // jika berasal dari pemesanan
    ];

    // Otomatis cast ke objek tanggal
    protected $casts = [
        'tanggal'     => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Relasi ke user (admin/operator) yang memproses pembayaran.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke konsumen (menggunakan no_identitas sebagai foreign key).
     */
    public function konsumen()
    {
       return $this->belongsTo(\App\Models\Konsumen::class, 'konsumen_id', 'no_identitas');
    }

    /**
     * Relasi ke pemesanan (jika terkait booking).
     */
   
}
