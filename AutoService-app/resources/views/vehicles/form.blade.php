@extends('layouts.app')

@section('title', ($vehicle ? 'Editar vehículo' : 'Registrar vehículo') . ' | AutoService Pro')

@section('content')
    <h1>{{ $vehicle ? 'Editar vehículo' : 'Registrar vehículo' }}</h1>

    <p class="descripcion">
        Cliente: {{ $client->name }}
    </p>

    <section class="tarjeta">
        <form
            class="formulario-cliente"
            method="POST"
            action="{{ $vehicle
                ? route('vehicles.update', $vehicle)
                : route('vehicles.store', $client) }}"
        >
            @csrf

            @if ($vehicle)
                @method('PATCH')
            @endif

            <div class="campo-cliente">
                <label for="plate">Placa</label>

                <input
                    type="text"
                    id="plate"
                    name="plate"
                    value="{{ old('plate', $vehicle?->plate) }}"
                    maxlength="20"
                    required
                >

                @error('plate')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="make">Marca</label>

                <input
                    type="text"
                    id="make"
                    name="make"
                    value="{{ old('make', $vehicle?->make) }}"
                    maxlength="80"
                    required
                >

                @error('make')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="model">Modelo</label>

                <input
                    type="text"
                    id="model"
                    name="model"
                    value="{{ old('model', $vehicle?->model) }}"
                    maxlength="80"
                    required
                >

                @error('model')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="model_year">Año</label>

                <input
                    type="number"
                    id="model_year"
                    name="model_year"
                    value="{{ old('model_year', $vehicle?->model_year) }}"
                    min="1000"
                    max="9999"
                    required
                >

                @error('model_year')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="acciones-cliente">
                <button type="submit" class="boton-cliente">
                    {{ $vehicle ? 'Guardar cambios' : 'Guardar vehículo' }}
                </button>

                <a
                    href="{{ route('clients.show', ['cliente' => $client->id]) }}"
                    class="enlace-cliente"
                >
                    Volver a la ficha del cliente
                </a>
            </div>
        </form>
    </section>
@endsection