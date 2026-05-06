<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalPrescription extends Model
{
    protected $fillable = [
        'patient_id',
        'medication_id',
        'user_id',
        'dose',
        'frequency',
        'route',
        'instructions'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class
        );
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(
            Medication::class
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