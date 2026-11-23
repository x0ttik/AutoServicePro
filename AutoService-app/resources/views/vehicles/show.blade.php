@extends('layouts.app')

@section('title', 'Ficha del vehículo | AutoService Pro')

@section('content')
    <h1>{{ $vehicle->make }} {{ $vehicle->model }}</h1>

    <p class="descripcion">
        Consulta los datos del vehículo y sus opciones.
    </p>

    @if (session('status'))
        <p class="exito-cliente" role="status">
            {{ session('status') }}
        </p>
    @endif

    <section class="tarjeta" aria-labelledby="titulo-vehiculo">
        <h2 id="titulo-vehiculo">Información del vehículo</h2>

        <dl class="ficha-cliente">
            <dt>Placa</dt>
            <dd>{{ $vehicle->plate }}</dd>

            <dt>Marca</dt>
            <dd>{{ $vehicle->make }}</dd>

            <dt>Modelo</dt>
            <dd>{{ $vehicle->model }}</dd>

            <dt>Año</dt>
            <dd>{{ $vehicle->model_year }}</dd>

            <dt>Cliente</dt>
            <dd>{{ $vehicle->client->name }}</dd>
        </dl>

        <div class="acciones-cliente">
            <a
                href="{{ route('vehicles.edit', $vehicle) }}"
                class="boton-cliente"
            >
                Editar vehículo
            </a>

            <a
                href="{{ route('work-orders.create', $vehicle) }}"
                class="boton-cliente"
            >
                Abrir orden de trabajo
            </a>

            <a
                href="{{ route('clients.show', ['cliente' => $vehicle->client_id]) }}"
                class="enlace-cliente"
            >
                Volver a la ficha del cliente
            </a>
        </div>
    </section>
@endsection