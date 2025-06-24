<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika mengikuti konvensi Laravel)
    protected $table = 'pembayaran';

    // Kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'tipe',
        'jumlah',
        'metode',
        'tanggal',
        'status',
        'bukti',
        'pemesanan_id', // tambahkan jika kolom ini ada di database
    ];

    // Casting otomatis ke objek tanggal (Carbon)
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /**
     * Relasi ke user yang melakukan pembayaran.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pemesanan (jika pembayaran terkait pemesanan).
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}
