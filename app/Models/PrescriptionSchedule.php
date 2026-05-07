<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionSchedule extends Model
{
    protected $fillable = [
        'medical_prescription_id',
        'scheduled_time',
        'label',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime:H:i',
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(MedicalPrescription::class, 'medical_prescription_id');
    }
}
