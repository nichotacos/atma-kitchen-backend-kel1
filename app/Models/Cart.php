<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cart';

    public $timestamps = true;

    protected $fillable = [
        'harga_total_cart'
    ];

    public function detailCart(): HasMany
    {
        return $this->hasMany(DetailCart::class, 'id_cart');
    }
}
