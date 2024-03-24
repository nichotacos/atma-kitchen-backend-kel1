<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penitip';

    protected $fillable = [
        'nama_penitip',
    ];

    public function Produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
