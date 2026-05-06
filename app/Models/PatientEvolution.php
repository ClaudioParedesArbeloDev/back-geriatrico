<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientEvolution extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'evolution',
        'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'datetime'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class
        );
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}