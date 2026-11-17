<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Servicios
Route::get('/servicios', function () {
    $services = \App\Models\Service::where('active', true)->orderBy('name')->get();
    return view('pages.services.index', compact('services'));
})->name('services.index');

// Estilistas
Route::get('/estilistas', function () {
    $stylists = \App\Models\Stylist::where('active', true)->orderBy('name')->get();
    return view('pages.stylists.index', compact('stylists'));
})->name('stylists.index');

// Productos (tienda)
Route::get('/productos', function () {
    $products = [
        ['icon' => '🧴', 'name' => 'Shampoo profesional',  'price' => '$12.000', 'rating' => 5, 'reviews' => 94,  'badge' => 'Popular'],
        ['icon' => '💧', 'name' => 'Aceite capilar',       'price' => '$15.000', 'rating' => 5, 'reviews' => 67,  'badge' => null],
        ['icon' => '✨', 'name' => 'Sérum reparador',      'price' => '$18.000', 'rating' => 5, 'reviews' => 128, 'badge' => 'Top ventas'],
        ['icon' => '💅', 'name' => 'Esmalte premium',      'price' => '$8.000',  'rating' => 4, 'reviews' => 42,  'badge' => null],
        ['icon' => '🌿', 'name' => 'Mascarilla nutritiva', 'price' => '$14.000', 'rating' => 5, 'reviews' => 81,  'badge' => 'Nuevo'],
        ['icon' => '🧖', 'name' => 'Mascarilla facial',    'price' => '$16.000', 'rating' => 4, 'reviews' => 55,  'badge' => null],
        ['icon' => '💆', 'name' => 'Aceite para barba',    'price' => '$13.000', 'rating' => 5, 'reviews' => 38,  'badge' => null],
        ['icon' => '🌸', 'name' => 'Perfume floral',       'price' => '$22.000', 'rating' => 5, 'reviews' => 203, 'badge' => 'Favorito'],
    ];
    return view('pages.store.index', compact('products'));
})->name('products.index');

// ⚠️ Selector de servicio (debe ir ANTES de bookings.create)
Route::get('/reservar', function () {
    $services = \App\Models\Service::where('active', true)->orderBy('name')->get();
    return view('pages.bookings.pick-service', compact('services'));
})->name('bookings.pick');

// Rutas de reservas
Route::get('/reservar/{service:slug}', [BookingController::class, 'create'])
    ->name('bookings.create');

Route::get('/api/slots', [BookingController::class, 'slots'])
    ->name('bookings.slots');
Route::get('/api/month-availability', [BookingController::class, 'monthAvailability'])
    ->name('bookings.month');

Route::middleware('auth')->group(function () {
    Route::post('/reservar/{service:slug}', [BookingController::class, 'store'])
        ->name('bookings.store');
    Route::get('/mis-reservas', [BookingController::class, 'index'])
        ->name('bookings.index');
    Route::delete('/mis-reservas/{booking}', [BookingController::class, 'destroy'])
        ->name('bookings.destroy');
});

// Dashboard (admin)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'admin'])->name('dashboard');

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
