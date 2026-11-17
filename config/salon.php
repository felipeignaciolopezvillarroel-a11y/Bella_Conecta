<?php

return [
    'hours' => [
        0 => null,                          // Domingo cerrado
        1 => ['10:00', '20:00'],
        2 => ['10:00', '20:00'],
        3 => ['10:00', '20:00'],
        4 => ['10:00', '20:00'],
        5 => ['10:00', '20:00'],
        6 => ['10:00', '18:00'],            // Sábado
    ],
    'slot_minutes' => 30,
    'max_days_ahead' => 60,

    // 🔒 Límite por usuario
    'max_active_bookings_per_user' => 3,    // máximo de reservas activas
];