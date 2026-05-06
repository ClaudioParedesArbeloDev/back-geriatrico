<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientBedAssignment extends Model
{
    protected $fillable = [
        'patient_id',
        'bed_id',
        'assigned_at',
        'released_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'released_at' => 'datetime'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class
        );
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(
            Bed::class
        );
    }
}