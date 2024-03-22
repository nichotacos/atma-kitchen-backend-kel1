<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_status';

    protected $fillable = [
        'detail_status'
    ];

    public function refund(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
