<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_unit';

    public $timestamps = false;

    protected $fillable = [
        'nama_unit'
    ];
}
