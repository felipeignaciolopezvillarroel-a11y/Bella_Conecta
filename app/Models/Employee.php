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

        // Si ya es una URL externa, base64 o una ruta local antigua
        if (Str::startsWith($this->photo, ['http://', 'https://', 'data:image/'])) {
            return $this->photo;
        }

        // Si estamos usando S3 (Supabase)
        if (config('filesystems.default') === 's3') {
            // Usamos la variable AWS_URL limpia (sin barra al final) + la ruta
            $baseUrl = rtrim(env('AWS_URL'), '/');
            $path = ltrim($this->photo, '/');
            return "{$baseUrl}/{$path}";
        }

        // Fallback para local
        return Storage::disk('public')->url($this->photo);
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
