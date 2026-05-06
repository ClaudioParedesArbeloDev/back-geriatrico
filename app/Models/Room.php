<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'number',
        'name',
        'floor',
        'capacity',
        'type',
        'status'
    ];

    public function beds(): HasMany
    {
        return $this->hasMany(
            Bed::class
        );
    }
}