<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cie10Code extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    
    public function scopeSearch($query, string $term)
    {
        return $query->where('code', 'like', $term . '%')
                     ->orWhere('description', 'like', '%' . $term . '%');
    }
}