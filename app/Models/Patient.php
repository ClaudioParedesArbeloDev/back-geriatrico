<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'notes',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'admission_date' => 'date',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function allergies(): BelongsToMany
    {
        return $this->belongsToMany(
            Allergy::class,
            'patient_allergies'
        )->withPivot([
            'severity',
            'reaction',
        ])->withTimestamps();
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(
            MedicalPrescription::class
        );
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(
            MedicalRecord::class
        );
    }

    public function bedAssignments(): HasMany
    {
        return $this->hasMany(
            PatientBedAssignment::class
        );
    }

    public function evolutions()
    {
        return $this->hasMany(
            PatientEvolution::class
        );
    }

    public function studies()
    {
        return $this->hasMany(
            MedicalStudy::class
        );
    }

    public function diagnoses()
    {
        return $this->hasMany(
            PatientDiagnosis::class
        );
    }
}
