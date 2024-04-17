<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $primaryKey = ['id_detail_resep', 'id_produk'];

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['id_detail_resep', 'id_produk'];
}
