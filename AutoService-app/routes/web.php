<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function (){
    return view('inicio', [
        'nombreTaller' => 'AutoServicePro',
    ]);
});

Route::get('/clientes', [ClienteController::class, 'index'])
    -> name('clientes.index');

