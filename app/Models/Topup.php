<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    protected $fillable = ['konsumen_id', 'nominal', 'bukti_transfer', 'status'];

    public function konsumen()
{
    return $this->belongsTo(Konsumen::class, 'konsumen_id', 'no_identitas');
}

}
