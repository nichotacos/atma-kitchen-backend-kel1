<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_bahan_baku';

    public $timestamps = false;

    protected $fillable = [
        'nama_bahan_baku',
        'stok_bahan_baku'
    ];

    public function unit(): HasMany
    {
        return $this->hasMany(Unit::class, 'id_unit');
    }
}
