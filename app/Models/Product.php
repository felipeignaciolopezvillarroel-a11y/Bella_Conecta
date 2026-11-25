<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // 👈 Faltaba esto
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'brand',
        'description',
        'image',
        'price',
        'discount_price',
        'stock',
        'active',
        'featured'
    ];

    protected $casts = [
        'active' => 'boolean',
        'featured' => 'boolean',
    ];

    // Generar slug y formatear nombre automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = mb_convert_case(trim((string) $model->name), MB_CASE_TITLE, 'UTF-8');

            $baseSlug = Str::slug($model->name) ?: 'producto';
            $slug = $baseSlug;
            $counter = 2;

            while (
                static::query()
                ->where('slug', $slug)
                ->when($model->exists, fn($q) => $q->where('id', '!=', $model->id))
                ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $model->slug = $slug;
        });
    }

    // Accessor para la URL de la imagen
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'data:image/', '/storage/'])) {
            return $this->image;
        }

        $baseUrl = 'https://gzphzgaajevxbwwegduy.supabase.co/storage/v1/object/public/fotos';
        $path = ltrim($this->image, '/');

        return "{$baseUrl}/{$path}";
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('stock', 'like', "%{$search}%");

            if (str_contains(strtolower($search), 'activ')) {
                $q->orWhere('active', true);
            }
            if (str_contains(strtolower($search), 'inactiv')) {
                $q->orWhere('active', false);
            }
        });
    }
}
