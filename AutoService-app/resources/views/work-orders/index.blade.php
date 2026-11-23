@extends('layouts.app')

@section('title', 'Órdenes de trabajo | AutoService Pro')

@section('content')
    <h1>Órdenes de trabajo</h1>

    <p class="descripcion">
        Consulta las órdenes registradas y el estado de cada trabajo.
    </p>

    <div class="acciones-cliente">
        <a
            href="{{ route('dashboard') }}"
            class="enlace-cliente"
        >
            Volver al inicio
        </a>
    </div>

    @if (session('error'))
        <p class="error-cliente" role="alert">
            {{ session('error') }}
        </p>
    @endif

    @if (session('status'))
        <p class="exito-cliente" role="status">
            {{ session('status') }}
        </p>
    @endif

    <section class="tarjeta" aria-labelledby="titulo-listado">
        <h2 id="titulo-listado">Listado de órdenes</h2>

        <div class="tabla-contenedor">
            <table class="tabla-clientes tabla-ordenes">
                <thead>
                    <tr>
                        <th scope="col">Folio</th>
                        <th scope="col">Vehículo</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Apertura</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->id }}</strong>
                            </td>

                            <td>
                                <strong>
                                    {{ $order->vehicle->make }}
                                    {{ $order->vehicle->model }}
                                </strong>

                                <span class="placa-orden">
                                    {{ $order->vehicle->plate }}
                                </span>
                            </td>

                            <td>
                                <span class="estado-orden estado-{{ $order->status }}">
                                    {{ $order->statusLabel() }}
                                </span>
                            </td>

                            <td class="fecha-orden">
                                {{ $order->created_at->timezone('America/Tijuana')->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('work-orders.show', $order) }}"
                                    class="boton-cliente"
                                    aria-label="Ver orden número {{ $order->id }}"
                                >
                                    Ver orden
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="tabla-vacia">
                                Todavía no hay órdenes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <nav class="paginacion-ordenes" aria-label="Páginas de órdenes">
                @if ($orders->onFirstPage())
                    <span class="pagina-deshabilitada">
                        Anterior
                    </span>
                @else
                    <a
                        href="{{ $orders->previousPageUrl() }}"
                        class="boton-cliente"
                        rel="prev"
                    >
                        Anterior
                    </a>
                @endif

                <span>
                    Página {{ $orders->currentPage() }}
                    de {{ $orders->lastPage() }}
                </span>

                @if ($orders->hasMorePages())
                    <a
                        href="{{ $orders->nextPageUrl() }}"
                        class="boton-cliente"
                        rel="next"
                    >
                        Siguiente
                    </a>
                @else
                    <span class="pagina-deshabilitada">
                        Siguiente
                    </span>
                @endif
            </nav>
        @endif
    </section>
@endsection