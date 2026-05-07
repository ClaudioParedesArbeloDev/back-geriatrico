<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name',
    'last_name',
    'dni',
    'birth_date',
    'email',
    'phone',
    'address',
    'employee_code',
    'license_number',
    'hire_date',
    'avatar',
    'is_active',
    'password',
])]

#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'birth_date'         => 'date',
            'hire_date'          => 'date',
            'is_active'          => 'boolean',
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Roles y especialidades
    // -------------------------------------------------------

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class)->withTimestamps();
    }

    // -------------------------------------------------------
    // Acciones clínicas del profesional
    // -------------------------------------------------------

    public function diagnoses(): HasMany
    {
        return $this->hasMany(PatientDiagnosis::class);
    }

    public function studies(): HasMany
    {
        return $this->hasMany(MedicalStudy::class);
    }

    public function evolutions(): HasMany
    {
        return $this->hasMany(PatientEvolution::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class);
    }

    public function vitalSigns(): HasMany
    {
        return $this->hasMany(VitalSign::class);
    }
}
