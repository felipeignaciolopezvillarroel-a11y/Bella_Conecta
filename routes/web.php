<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Servicios (público)
Route::get('/servicios', function () {
    $services = \App\Models\Service::where('active', true)->orderBy('name')->get();
    return view('pages.services.index', compact('services'));
})->name('services.index');

// Equipo (público) — solo profesionales
Route::get('/equipo', function () {
    $team = \App\Models\Employee::where('active', true)
        ->where('category', 'profesional')
        ->orderBy('name')
        ->get();

    return view('pages.team.index', compact('team'));
})->name('team.index');

// Productos (público)
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

// Selector de servicio
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

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Servicios
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    // Empleados
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
    // Productos
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    // Reservas
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('bookings.destroy');
    // Usuarios
    Route::resource('users', UserController::class)
        ->only(['index', 'show', 'destroy']);
});

// Portal del empleado
Route::middleware(['auth', 'employee'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', function () {
        return view('portal.dashboard');
    })->name('dashboard');
});

require __DIR__ . '/auth.php';
