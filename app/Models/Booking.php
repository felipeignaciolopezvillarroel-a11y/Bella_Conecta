<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'service_id', 'stylist_id', 'date',
        'start_time', 'end_time', 'status', 'notes', 'code',
    ];

    protected $casts = [
        'date'       => 'date',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function stylist(): BelongsTo { return $this->belongsTo(Stylist::class); }

    public static function generateCode(): string
    {
        return 'BC-' . now()->format('Y') . '-' . str_pad((string) (static::count() + 1), 5, '0', STR_PAD_LEFT);
    }
}