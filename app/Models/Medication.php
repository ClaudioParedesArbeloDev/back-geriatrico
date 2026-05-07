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
        'presentation',
        'concentration',
        'drug_form',
        'contraindications',
        'controlled',
    ];

    protected $casts = [
        'controlled' => 'boolean',
    ];

    public function prescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class);
    }
}
