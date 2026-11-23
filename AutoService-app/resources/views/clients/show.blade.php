@extends('layouts.app')

@section('title', 'Ficha del cliente | AutoService Pro')

@section('content')
    <h1>Ficha del cliente</h1>

    <p class="descripcion">
        Consulta los datos registrados del cliente.
    </p>

    @if (session('success'))
        <p class="exito-cliente" role="status">
            {{ session('success') }}
        </p>
    @endif

    <section class="tarjeta" aria-labelledby="titulo-ficha">
        <h2 id="titulo-ficha">{{ $cliente->name }}</h2>

        <dl class="ficha-cliente">
            <dt>Nombre completo</dt>
            <dd>{{ $cliente->name }}</dd>

            <dt>Teléfono</dt>
            <dd>{{ $cliente->phone }}</dd>
        </dl>

    <div class="acciones-cliente">
        <a
            href="{{ route('clients.edit', ['cliente' => $cliente->id]) }}"
            class="boton-cliente"
        >
            Editar cliente
        </a>

        <a
            href="{{ route('clients.index') }}"
            class="enlace-cliente"
        >
            Volver al listado
        </a>
    </div>
    </section>

    <section class="tarjeta vehiculos-cliente" aria-labelledby="titulo-vehiculos">
        <h2 id="titulo-vehiculos">Vehículos asociados</h2>

        <div class="acciones-cliente">
            <a
                href="{{ route('vehicles.create', ['client' => $cliente->id]) }}"
                class="boton-cliente"
            >
                Registrar vehículo
            </a>
        </div>

        <div class="tabla-contenedor">
            <table class="tabla-clientes">
                <thead>
                    <tr>
                        <th scope="col">Placa</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Año</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cliente->vehicles as $vehiculo)
                        <tr>
                            <td>{{ $vehiculo->plate }}</td>
                            <td>{{ $vehiculo->make }}</td>
                            <td>{{ $vehiculo->model }}</td>
                            <td>{{ $vehiculo->model_year }}</td>
                            <td>
                                <a
                                    href="{{ route('vehicles.show', ['vehicle' => $vehiculo->id]) }}"
                                    class="boton-cliente"
                                    aria-label="Ver vehículo con placa {{ $vehiculo->plate }}"
                                >
                                    Ver vehículo
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="tabla-vacia">
                                Este cliente no tiene vehículos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
