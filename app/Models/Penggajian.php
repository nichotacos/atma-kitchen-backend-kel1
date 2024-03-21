<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penggajian';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
