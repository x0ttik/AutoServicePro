@extends('layouts.app')

@section('title', 'Abrir orden de trabajo | AutoService Pro')

@section('content')
    <h1>Abrir orden de trabajo</h1>

    <p class="descripcion">
        Describe el trabajo solicitado para este vehículo.
    </p>

    <section class="tarjeta">
        <h2>Datos de recepción</h2>

        <dl class="ficha-cliente">
            <dt>Cliente</dt>
            <dd>{{ $vehicle->client->name }}</dd>

            <dt>Vehículo</dt>
            <dd>{{ $vehicle->make }} {{ $vehicle->model }}</dd>

            <dt>Placa</dt>
            <dd>{{ $vehicle->plate }}</dd>
        </dl>

        <form
            class="formulario-cliente"
            method="POST"
            action="{{ route('work-orders.store', $vehicle) }}"
        >
            @csrf

            <div class="campo-cliente">
                <label for="description">Trabajo solicitado</label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    required
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="acciones-cliente">
                <button type="submit" class="boton-cliente">
                    Abrir orden
                </button>

                <a
                    href="{{ route('vehicles.show', $vehicle) }}"
                    class="enlace-cliente"
                >
                    Volver al vehículo
                </a>
            </div>
        </form>
    </section>
@endsection