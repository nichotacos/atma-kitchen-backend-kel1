<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_unit';

    public $timestamps = true;

    protected $fillable = [
        'nama_unit'
    ];

    public function bahanBaku(): HasMany
    {
        return $this->hasMany(BahanBaku::class, 'id_bahan_baku');
    }
}
