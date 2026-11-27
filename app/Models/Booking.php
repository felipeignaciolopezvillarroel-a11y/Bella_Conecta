<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'modality',
        'address',
        'notes',
        'code',
    ];

    protected $casts = [
        'date'       => 'date',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function generateCode(): string
    {
        $year = now()->format('Y');
        $nextNumber = (static::max('id') ?? 0) + 1;

        do {
            $code = 'BC-' . $year . '-' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::where('code', $code)->exists());

        return $code;
    }
}