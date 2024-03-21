<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kemasan';

    protected $fillable = [
        'detail_kemasan',
        'stok_kemasan',
    ];
}
