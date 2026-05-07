<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'dni',
        'birth_date',
        'gender',
        'blood_type',
        'admission_date',
        'mobility_status',
        'dependency_level',
        'emergency_phone',
        'obra_social',
        'numero_afiliado',
        'notes',
        'status',
    ];

    protected $casts = [
        'birth_date'     => 'date',
        'admission_date' => 'date',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function primaryContact(): HasOne
    {
        return $this->hasOne(PatientContact::class)->where('is_primary', true);
    }

    public function allergies(): BelongsToMany
    {
        return $this->belongsToMany(Allergy::class, 'patient_allergies')
            ->withPivot(['severity', 'reaction'])
            ->withTimestamps();
    }

    public function bedAssignments(): HasMany
    {
        return $this->hasMany(PatientBedAssignment::class);
    }

    public function currentBedAssignment(): HasOne
    {
        return $this->hasOne(PatientBedAssignment::class)
            ->whereNull('released_at')
            ->latest('assigned_at');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(PatientDiagnosis::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class);
    }

    public function activePrescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class)->where('is_active', true);
    }

    public function studies(): HasMany
    {
        return $this->hasMany(MedicalStudy::class);
    }

    public function evolutions(): HasMany
    {
        return $this->hasMany(PatientEvolution::class);
    }

    public function vitalSigns(): HasMany
    {
        return $this->hasMany(VitalSign::class);
    }

    public function latestVitalSigns(): HasOne
    {
        return $this->hasOne(VitalSign::class)->latestOfMany('recorded_at');
    }
}
