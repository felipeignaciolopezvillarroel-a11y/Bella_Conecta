<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'role',
        'category',
        'bio',
        'photo',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        // Si ya es una URL completa (http) o base64, la dejamos pasar
        if (Str::startsWith($this->photo, ['http://', 'https://', 'data:image/'])) {
            return $this->photo;
        }

        // FORZAMOS la URL de Supabase directamente
        $baseUrl = 'https://gzphzgaajevxbwwegduy.supabase.co/storage/v1/object/public/fotos';

        // Limpiamos la ruta para que no tenga barras duplicadas
        $path = ltrim($this->photo, '/');

        return "{$baseUrl}/{$path}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service')->withTimestamps();
    }
}
