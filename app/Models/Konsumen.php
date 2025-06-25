<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_identitas';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_identitas',
        'nama',
        'email',
        'no_telepon',
        'saldo',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'saldo' => 'float',
    ];

    // Relasi: Konsumen memiliki banyak topup
    public function topups()
    {
        return $this->hasMany(Topup::class, 'konsumen_id', 'no_identitas');
    }

    // Relasi: Konsumen memiliki banyak pemesanan
   

    // Relasi: Konsumen memiliki banyak pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'konsumen_id', 'no_identitas');
    }

    // Auto-generate no_identitas saat pembuatan
    protected static function booted(): void
    {
        static::creating(function (Konsumen $konsumen) {
            if (empty($konsumen->no_identitas)) {
                $latest = self::orderBy('no_identitas', 'desc')->first();
                $lastNumber = $latest ? (int) substr($latest->no_identitas, 3) : 0;
                $konsumen->no_identitas = 'USR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
