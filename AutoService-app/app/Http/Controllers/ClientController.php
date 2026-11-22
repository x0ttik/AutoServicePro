<?php

namespace App\Http\Controllers;


// NOTA: falta implementar desde la edicion de vehiculos por que 
// se necesita rehacer este modulo, en VehicleController se debera cambiar
// la importacion del model y el nombre de la clase en el uso del modelo Client



//ya esta
use App\Models\Client;

class ClientController extends Controller{
    public function index(){
        $clients = Client::orderBy('id') -> get();

        return view('clients.index', [
            'clients' => $clients,
        ]);
    }
}

