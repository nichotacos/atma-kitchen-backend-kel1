<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukHampers extends Model
{
    use HasFactory;

    protected $primaryKey = ['id_produk', 'id_hampers'];

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['id_produk', 'id_hampers'];
}
