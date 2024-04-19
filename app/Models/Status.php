<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_status';

    public $timestamps = true;

    protected $fillable = [
        'detail_status'
    ];

    public function refund(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
