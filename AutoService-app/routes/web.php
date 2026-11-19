<?php

use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [SessionController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {

    //Aqui van las rutas que requieren autentificacion para acceder

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::post('/logout', [SessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/servicios', [ServiceController::class, 'index'])
        ->name('services.index');
});