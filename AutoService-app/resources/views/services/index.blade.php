@extends('layouts.app')

@section('title', 'Catálogo de servicios | AutoService Pro')

@section('content')
    <h1>Catálogo de servicios</h1>
    <p class="descripcion">Consulta los servicios y precios base del taller.</p>

    @if (session('status'))
        <p class="exito-cliente" role="status">{{ session('status') }}</p>
    @endif

    @can('manage-services')
        <div class="acciones-cliente">
            <a href="{{ route('services.create') }}" class="boton-cliente">
                Nuevo servicio
            </a>
        </div>
    @endcan

    <section class="tarjeta" aria-labelledby="titulo-servicios">
        <h2 id="titulo-servicios">Listado de servicios</h2>

        <div class="tabla-contenedor">
            <table class="tabla-clientes tabla-servicios">
                <thead>
                    <tr>
                        <th scope="col">Servicio</th>
                        <th scope="col">Precio base (MXN)</th>
                        <th scope="col">Estado</th>
                        @can('manage-services')
                            <th scope="col">Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->name }}</td>
                            <td class="precio-servicio">
                                ${{ number_format($servicio->base_price_cents / 100, 2) }}
                            </td>
                            <td>
                                <span class="estado-servicio {{ $servicio->is_active ? 'servicio-activo' : 'servicio-inactivo' }}">
                                    {{ $servicio->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            @can('manage-services')
                                <td>
                                    <div class="acciones-servicio">
                                        <a
                                            href="{{ route('services.edit', $servicio) }}"
                                            class="boton-cliente"
                                            aria-label="Editar servicio {{ $servicio->name }}"
                                        >
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('services.update-status', $servicio) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $servicio->is_active ? '0' : '1' }}">
                                            <button
                                                type="submit"
                                                class="boton-cliente boton-estado-servicio"
                                                aria-label="{{ $servicio->is_active ? 'Desactivar' : 'Activar' }} servicio {{ $servicio->name }}"
                                            >
                                                {{ $servicio->is_active ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('manage-services') ? 4 : 3 }}" class="tabla-vacia">
                                Todavía no hay servicios disponibles para esta cuenta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
