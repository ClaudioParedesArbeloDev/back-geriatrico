<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected $fillable = [
        'nombre_comercial',
        'presentacion',
        'accion_farmacologica',
        'principio_activo',
        'laboratorio',
        'porcentaje',
        'seccion',
    ];

    protected $casts = [
        'porcentaje' => 'integer',
    ];

  

    public function prescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class);
    }

    

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('nombre_comercial',      'like', "%{$term}%")
              ->orWhere('principio_activo',     'like', "%{$term}%")
              ->orWhere('accion_farmacologica', 'like', "%{$term}%");
        });
    }

    public function scopePorcentaje(Builder $query, int $porcentaje): Builder
    {
        return $query->where('porcentaje', $porcentaje);
    }

    public function scopeSeccion(Builder $query, string $seccion): Builder
    {
        return $query->where('seccion', $seccion);
    }
}