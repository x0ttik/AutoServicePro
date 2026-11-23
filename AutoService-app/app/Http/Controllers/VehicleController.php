<?php


//ya esta
namespace App\Http\Controllers;

use App\Http\Requests\SaveVehicleRequest;
use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function create(Client $client): View
    {
        return view('vehicles.form', [
            'client' => $client,
            'vehicle' => null,
        ]);
    }

    public function store(SaveVehicleRequest $request, Client $client): RedirectResponse
    {
        $vehicle = new Vehicle($request->validated());
        $vehicle->client()->associate($client);

        $this->persistVehicle($vehicle);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('status', 'Vehículo registrado correctamente.');
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.form', [
            'client' => $vehicle->client,
            'vehicle' => $vehicle,
        ]);
    }

    public function update(SaveVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->fill($request->validated());

        $this->persistVehicle($vehicle);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('status', 'Vehículo actualizado correctamente.');
    }

    private function persistVehicle(Vehicle $vehicle): void
    {
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
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load('client');

        return view('vehicles.show', ['vehicle' => $vehicle]);
    }
}