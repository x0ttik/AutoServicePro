@extends('layouts.app')

@section('title', 'Clientes | AutoService Pro')

@section('content')
    <h1>Clientes del taller</h1>

    <p class="descripcion">
        Consulta los clientes registrados en el taller.
    </p>

    @if (session('success'))
        <p class="exito-cliente" role="status">
            {{ session('success') }}
        </p>
    @endif

    <div class="acciones-cliente">
        <a
            href="{{ route('clientes.create') }}"
            class="boton-cliente"
        >
            Registrar cliente
        </a>
    </div>

    <section class="tarjeta" aria-labelledby="titulo-clientes">
        <h2 id="titulo-clientes">Listado de clientes</h2>

        <div class="tabla-contenedor">
        <table class="tabla-clientes">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->name }}</td>
                        <td>{{ $cliente->phone }}</td>
                        <td>
                            <a
                                href="{{ route('clientes.show', ['cliente' => $cliente->id]) }}"
                                class="boton-cliente"
                                aria-label="Ver ficha de {{ $cliente->name }}"
                            >
                                Ver ficha
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="tabla-vacia">
                            No hay clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </section>
@endsection