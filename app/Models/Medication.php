<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected $fillable = [
        'code',
        'name',
        'generic_name',
        'laboratory',
        'presentation'
    ];

    public function prescriptions(): HasMany
    {
        return $this->hasMany(
            MedicalPrescription::class
        );
    }
}