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
        return view('services.form', ['service' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $service = new Service();
        $this->saveService($request, $service);

        return redirect()->route('services.index')
            ->with('status', 'Servicio registrado correctamente.');
    }

    public function edit(Service $service): View
    {
        return view('services.form', ['service' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->saveService($request, $service);

        return redirect()->route('services.index')
            ->with('status', 'Servicio actualizado correctamente.');
    }

    public function updateStatus(Request $request, Service $service): RedirectResponse
    {
        $datos = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $service->is_active = (bool) $datos['is_active'];
        $service->save();

        return redirect()->route('services.index')
            ->with('status', $service->is_active ? 'Servicio activado.' : 'Servicio desactivado.');
    }

    private function saveService(Request $request, Service $service): void
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

        $partes = explode('.', (string) $datos['price'], 2);
        $pesos = (int) $partes[0];
        $centavos = (int) str_pad($partes[1] ?? '', 2, '0', STR_PAD_RIGHT);

        $service->name = $datos['name'];
        $service->base_price_cents = ($pesos * 100) + $centavos;
        $service->save();
    }
}