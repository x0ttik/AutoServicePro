<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller{
    public function index(){
        $clientes = Cliente::orderBy('id') -> get();

        return view('clientes', [
            'clientes' => $clientes,
        ]);
    }

    public function create(){
        return view('clientes.create');
    }

    public function store(Request $request){
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
        ]);

        $cliente = new Cliente();
        $cliente -> name = $datos['name'];
        $cliente -> phone = $datos['phone'];
        $cliente -> save();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Cliente $cliente){
        return view('clientes.show', [
            'cliente' => $cliente,
        ]);
    }

    public function edit(Cliente $cliente){
        return view('clientes.edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, Cliente $cliente){
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
        ]);

        $cliente -> name = $datos['name'];
        $cliente -> phone = $datos['phone'];
        $cliente -> save();

        return redirect()
            ->route('clientes.show', ['cliente' => $cliente -> id])
            ->with('success', 'Cliente actualizado correctamente.');
    }

}
