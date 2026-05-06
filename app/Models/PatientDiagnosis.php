<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDiagnosis extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'diagnosis',
        'notes',
        'diagnosed_at'
    ];

    protected $casts = [
        'diagnosed_at' => 'date'
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