
<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServiceController;
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

    // Crear cliente nuevo
    Route::get('/clientes/crear', [ClienteController:: class, 'create'])
        -> name('clientes.create');

    // Guardar cliente nuevo
    Route::post('/clientes', [ClienteController:: class, 'store'])
        -> name('clientes.store');

    // Consultar cliente
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])
        -> whereNumber('cliente')
        -> name('clientes.show');

    // Formulario de edicion
    Route::get('/clientes/{cliente}/editar', [ClienteController::class, 'edit'])
        -> whereNumber('cliente')
        -> name('clientes.edit');

    // Guardar cambios
    Route::put('/clientes/{cliente}', [ClienteController:: class, 'update'])
        -> whereNumber('cliente')
        -> name('clientes.update');

    // Módulo de servicios - Cristopher
    Route::get('/servicios', [ServiceController::class, 'index'])
        ->name('services.index');

    // Administración de servicios
    Route::middleware('can:manage-services')->group(function () {

        Route::get('/servicios/crear', [ServiceController::class, 'create'])
            ->name('services.create');

        Route::post('/servicios', [ServiceController::class, 'store'])
            ->name('services.store');

    });

});

