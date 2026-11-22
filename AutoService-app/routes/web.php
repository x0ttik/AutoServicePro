
<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Página principal
Route::get('/', function () {
    return view('inicio', [
        'nombreTaller' => 'AutoServicePro',
    ]);
});

// Rutas para usuarios no autenticados
Route::middleware('guest')->group(function () {

    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [SessionController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');

});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    // Cerrar sesión
    Route::post('/logout', [SessionController::class, 'destroy'])
        ->name('logout');

    // Módulo de clientes - Paul
    Route::get('/clientes', [ClienteController::class, 'index'])
        ->name('clientes.index');

    // Modulos de vehiculos
    Route::get('/clientes/{client}/vehiculos/crear', [VehicleController::class, 'create'])
        ->name('vehicles.create');

    Route::post('/clientes/{client}/vehiculos', [VehicleController::class, 'store'])
        ->name('vehicles.store');

    Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])
        ->name('vehicles.show');

    // Módulo de servicios - Cristopher
    Route::get('/servicios', [ServiceController::class, 'index'])
        ->name('services.index');

    // Administración de servicios para el rol 'admin'
    Route::middleware('can:manage-services')->group(function () {

        Route::get('/servicios/crear', [ServiceController::class, 'create'])
            ->name('services.create');

        Route::post('/servicios', [ServiceController::class, 'store'])
            ->name('services.store');

        Route::get('/servicios/{service}/editar', [ServiceController::class, 'edit'])
            ->name('services.edit');

        Route::patch('/servicios/{service}', [ServiceController::class, 'update'])
            ->name('services.update');

        Route::patch('/servicios/{service}/estado', [ServiceController::class, 'updateStatus'])
            ->name('services.update-status');

    });

});

