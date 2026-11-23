<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderItemController;
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
    Route::get('/clientes', [ClientController::class, 'index'])
        ->name('clients.index');

    // Modulos de vehiculos
    Route::get('/clientes/{client}/vehiculos/crear', [VehicleController::class, 'create'])
        ->name('vehicles.create');

    Route::post('/clientes/{client}/vehiculos', [VehicleController::class, 'store'])
        ->name('vehicles.store');

    Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])
        ->name('vehicles.show');

    // Modulo de edicion de vehiculos    
    Route::get('/vehiculos/{vehicle}/editar', [VehicleController::class, 'edit'])
        ->name('vehicles.edit');

    Route::patch('/vehiculos/{vehicle}', [VehicleController::class, 'update'])
        ->name('vehicles.update');    

    // Crear cliente nuevo
    Route::get('/clientes/crear', [ClientController:: class, 'create'])
        -> name('clients.create');

    // Guardar cliente nuevo
    Route::post('/clientes', [ClientController:: class, 'store'])
        -> name('clients.store');

    // Consultar cliente
    Route::get('/clientes/{cliente}', [ClientController::class, 'show'])
        -> whereNumber('cliente')
        -> name('clients.show');

    // Formulario de edicion
    Route::get('/clientes/{cliente}/editar', [ClientController::class, 'edit'])
        -> whereNumber('cliente')
        -> name('clients.edit');

    // Guardar cambios
    Route::put('/clientes/{cliente}', [ClientController:: class, 'update'])
        -> whereNumber('cliente')
        -> name('clients.update');

    // Módulo de servicios - Cristopher
    Route::get('/servicios', [ServiceController::class, 'index'])
        ->name('services.index');

    // Modulo de ordenes
    Route::get('/ordenes', [WorkOrderController::class, 'index'])
        ->name('work-orders.index');

    Route::get('/ordenes/{workOrder}', [WorkOrderController::class, 'show'])
        ->name('work-orders.show');

    Route::get('/vehiculos/{vehicle}/ordenes/crear', [WorkOrderController::class, 'create'])
        ->name('work-orders.create')
        ->missing(fn () => redirect()->route('work-orders.index')
            ->with('error', 'El vehículo no existe. Selecciona uno registrado.'));

    Route::post('/vehiculos/{vehicle}/ordenes', [WorkOrderController::class, 'store'])
        ->name('work-orders.store')
        ->missing(fn () => redirect()->route('work-orders.index')
            ->with('error', 'El vehículo no existe. Selecciona uno registrado.')); 
            
    Route::scopeBindings()->group(function () {
        Route::post('/ordenes/{workOrder}/partidas', [WorkOrderItemController::class, 'store'])
            ->name('work-orders.items.store');

        Route::patch('/ordenes/{workOrder}/partidas/{item}', [WorkOrderItemController::class, 'update'])
            ->name('work-orders.items.update');

        Route::delete('/ordenes/{workOrder}/partidas/{item}', [WorkOrderItemController::class, 'destroy'])
            ->name('work-orders.items.destroy');

        Route::patch('/ordenes/{workOrder}/estado', [WorkOrderController::class, 'updateStatus'])
            ->name('work-orders.update-status');
    });
        

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

