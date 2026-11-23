<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clientes = Client::orderBy('id')->get();

        return view('clients.index', [
            'clientes' => $clientes,
        ]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
        ]);

        $cliente = new Client;
        $cliente->name = $datos['name'];
        $cliente->phone = $datos['phone'];
        $cliente->save();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Client $cliente)
    {
        $cliente->load(['vehicles' => fn ($query) => $query->orderBy('id')]);

        return view('clients.show', [
            'cliente' => $cliente,
        ]);
    }

    public function edit(Client $cliente)
    {
        return view('clients.edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, Client $cliente)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
        ]);

        $cliente->name = $datos['name'];
        $cliente->phone = $datos['phone'];
        $cliente->save();

        return redirect()
            ->route('clients.show', ['cliente' => $cliente->id])
            ->with('success', 'Cliente actualizado correctamente.');
    }
}
