<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalStudy extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'study_type',
        'conclusion',
        'file_path',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function hasFile(): bool
    {
        return ! is_null($this->file_path);
    }
}
