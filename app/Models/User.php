<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'system_role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'system_role',
    ];

    /**
     * Reservas del usuario
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Empleado asociado (si el usuario es empleado)
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Scope: usuarios de la web (sin Employee asociado)
     */
    public function scopeWebUsers($query)
    {
        return $query->whereDoesntHave('employee');
    }

    // Helpers de rol
    public function isAdmin(): bool
    {
        return $this->system_role === 'admin';
    }

    public function isEmpleado(): bool
    {
        return $this->system_role === 'empleado';
    }

    public function isStaff(): bool
    {
        return in_array($this->system_role, ['empleado', 'admin']);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}