<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'duration_minutes',
        'price',
        'active',
        'featured',
        'features',
    ];

    protected $casts = [
        'active' => 'boolean',
        'featured' => 'boolean',
        'features' => 'array',
    ];

    // 1. Mutador para el Nombre (Título)
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8"),
            set: fn(string $value) => mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8"),
        );
    }

    // 2. Mutador para la Descripción (Primera letra de la frase en mayúscula)
    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => $value ? ucfirst(trim($value)) : null,
        );
    }

    // ⚠️ MUTADOR features() ELIMINADO
    // El cast 'features' => 'array' ya se encarga de la serialización.
    // El formateo (ucfirst/trim) se hace en el controller.
    // Tener ambos (mutador + cast array) causaba que Eloquent
    // expandiera el array de features como una clave "0" en el insert.

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_service')->withTimestamps();
    }
}
