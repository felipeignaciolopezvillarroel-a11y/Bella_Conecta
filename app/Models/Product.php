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
            $model->name = mb_convert_case(trim($model->name), MB_CASE_TITLE, "UTF-8");
            $model->slug = Str::slug($model->name);
        });
    }

    // Accessor para la URL de la imagen
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        // Si es una URL externa (como Unsplash) o base64
        if (Str::startsWith($this->image, ['http://', 'https://', 'data:image/', '/storage/'])) {
            return $this->image;
        }

        // Si es un archivo subido, lo busca en el disco público
        return Storage::disk('public')->url($this->image);
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
