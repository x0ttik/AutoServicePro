<?php

namespace App\Http\Controllers;

use App\Models\Cliente;

class ClienteController extends Controller{
    public function index(){
        $clientes = Cliente::orderBy('id') -> get();

        return view('clientes', [
            'clientes' => $clientes,
        ]);
    }
}
