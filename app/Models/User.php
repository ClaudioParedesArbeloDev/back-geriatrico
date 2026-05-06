<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'hire_date' => 'date',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()
            ->where('name', $roleName)
            ->exists();
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(
            Specialty::class
        )->withTimestamps();
    }

    public function diagnoses()
    {
        return $this->hasMany(
            PatientDiagnosis::class
        );
    }

    public function studies()
    {
        return $this->hasMany(
            MedicalStudy::class
        );
    }

    public function evolutions()
    {
        return $this->hasMany(
            PatientEvolution::class
        );
    }

    public function prescriptions()
    {
        return $this->hasMany(
            MedicalPrescription::class
        );
    }
}
