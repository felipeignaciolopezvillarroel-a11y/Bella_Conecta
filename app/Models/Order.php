<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total',
        'status',
        'delivered_at',   // 👈 Agregado para el portal de recepcionista
        'delivered_by',   // 👈 Agregado para el portal de recepcionista
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    /**
     * Items de la orden
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Cliente que realizó la compra
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Empleado que entregó la orden en tienda
     */
    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->code)) {
                $order->code = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }
}