<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Models\Cliente;
use App\Models\Vehicle;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function create(Cliente $client): View
    {
        return view('vehicles.create', ['client' => $client]);
    }

    public function store(StoreVehicleRequest $request, Cliente $client): RedirectResponse
    {
        $vehicle = new Vehicle($request->validated());
        $vehicle->client()->associate($client);

        try {
            $vehicle->save();
        } catch (UniqueConstraintViolationException $exception) {
            if (! str_contains($exception->getMessage(), '"vehicles_plate_key"')) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'plate' => 'Ya existe un vehículo con esa placa.',
            ]);
        }

        return redirect()->route('vehicles.show', $vehicle)
            ->with('status', 'Vehículo registrado correctamente.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load('client');

        return view('vehicles.show', ['vehicle' => $vehicle]);
    }
}