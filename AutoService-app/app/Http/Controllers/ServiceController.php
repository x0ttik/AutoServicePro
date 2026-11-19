<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $esAdministrador = $request->user()->role === 'admin';

        $consulta = Service::query()->orderBy('name');

        if (! $esAdministrador) {
            $consulta->where('is_active', true);
        }

        $servicios = $consulta->get();

        return view('services.index', [
            'servicios' => $servicios,
        ]);
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'price' => [
                'bail',
                'required',
                'regex:/\A[0-9]+(?:\.[0-9]{1,2})?\z/',
                'numeric',
                'min:0.01',
                'max:21474836.47',
            ],
        ], [
            'name.required' => 'Escribe el nombre del servicio.',
            'name.max' => 'El nombre puede tener hasta 120 caracteres.',
            'price.required' => 'Escribe el precio base.',
            'price.regex' => 'Usa un precio con hasta dos decimales, por ejemplo 350.50.',
            'price.min' => 'El precio debe ser mayor que cero.',
            'price.max' => 'El precio supera el importe máximo permitido.',
        ]);

        $partes = explode('.', $datos['price'], 2);
        $pesos = (int) $partes[0];
        $centavos = (int) str_pad($partes[1] ?? '', 2, '0', STR_PAD_RIGHT);

        $servicio = new Service();
        $servicio->name = $datos['name'];
        $servicio->base_price_cents = ($pesos * 100) + $centavos;
        $servicio->save();

        return redirect()->route('services.index')
            ->with('status', 'Servicio registrado correctamente.');
    }
}