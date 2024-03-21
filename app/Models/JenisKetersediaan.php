<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKetersediaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jenis_ketersediaan';

    protected $fillable = [
        'detail_ketersediaan'
    ];
}
