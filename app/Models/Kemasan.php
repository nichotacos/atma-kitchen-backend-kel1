<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kemasan';

    public $timestamps = true;

    protected $fillable = [
        'detail_kemasan',
        'stok_kemasan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
