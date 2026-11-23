@extends('layouts.app')

@section('title', 'Orden #' . $order->id . ' | AutoService Pro')

@section('content')
    <h1>Orden #{{ $order->id }}</h1>

    <p class="descripcion">
        Consulta el trabajo solicitado y administra su cotización.
    </p>

    <div class="acciones-cliente">
        <a
            href="{{ route('work-orders.index') }}"
            class="enlace-cliente"
        >
            Ver todas las órdenes
        </a>

        <a
            href="{{ route('vehicles.show', $order->vehicle) }}"
            class="enlace-cliente"
        >
            Volver al vehículo
        </a>
    </div>

    @if (session('status'))
        <p class="exito-cliente" role="status">
            {{ session('status') }}
        </p>
    @endif

    <section class="tarjeta" aria-labelledby="titulo-orden">
        <h2 id="titulo-orden">Información de la orden</h2>

        <dl class="ficha-cliente">
            <dt>Cliente</dt>
            <dd>{{ $order->vehicle->client->name }}</dd>

            <dt>Vehículo</dt>
            <dd>
                {{ $order->vehicle->make }}
                {{ $order->vehicle->model }}
                · {{ $order->vehicle->plate }}
            </dd>

            <dt>Trabajo solicitado</dt>
            <dd class="descripcion-trabajo">{{ $order->description }}</dd>

            <dt>Estado</dt>
            <dd>
                <span class="estado-orden">
                    {{ $order->statusLabel() }}
                </span>
            </dd>

            <dt>Abierta por</dt>
            <dd>{{ $order->creator->name }}</dd>

            <dt>Fecha de apertura</dt>
            <dd>
                {{ $order->created_at->timezone('America/Tijuana')->format('d/m/Y H:i') }}
            </dd>

            @if ($order->delivered_at)
                <dt>Fecha de entrega</dt>
                <dd>
                    {{ $order->delivered_at->timezone('America/Tijuana')->format('d/m/Y H:i') }}
                </dd>
            @endif
        </dl>

        @php
            $nextStatus = $order->nextStatus();

            $actionLabel = match ($nextStatus) {
                'in_progress' => 'Iniciar trabajo',
                'ready' => 'Marcar lista para entrega',
                'delivered' => 'Confirmar entrega',
                default => null,
            };
        @endphp

        @foreach ($errors->getBag('orderTransition')->all() as $error)
            <p class="error-cliente" role="alert">
                {{ $error }}
            </p>
        @endforeach

        @if ($actionLabel !== null)
            <form
                method="POST"
                action="{{ route('work-orders.update-status', $order) }}"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="expected_status"
                    value="{{ $order->status }}"
                >

                <input
                    type="hidden"
                    name="target_status"
                    value="{{ $nextStatus }}"
                >

                <button type="submit" class="boton-cliente">
                    {{ $actionLabel }}
                </button>
            </form>
        @endif
    </section>

    <section
        class="tarjeta cotizacion-orden"
        aria-labelledby="titulo-cotizacion"
    >
        <h2 id="titulo-cotizacion">Cotización</h2>

        @if ($errors->any())
            <ul class="error-cliente" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if ($order->status === 'open' && $services->isNotEmpty())
            <form
                class="formulario-cliente"
                method="POST"
                action="{{ route('work-orders.items.store', $order) }}"
            >
                @csrf

                <div class="campo-cliente">
                    <label for="service_id">Servicio</label>

                    <select id="service_id" name="service_id" required>
                        <option value="">Selecciona un servicio</option>

                        @foreach ($services as $service)
                            <option
                                value="{{ $service->id }}"
                                @selected(old('service_id') == $service->id)
                            >
                                {{ $service->name }}
                                · ${{ number_format($service->base_price_cents / 100, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="campo-cliente">
                    <label for="new_quantity">Cantidad</label>

                    <input
                        id="new_quantity"
                        name="quantity"
                        type="number"
                        min="1"
                        max="32767"
                        step="1"
                        value="{{ old('quantity', 1) }}"
                        required
                    >
                </div>

                <button type="submit" class="boton-cliente">
                    Agregar servicio
                </button>
            </form>
        @elseif ($order->status === 'open')
            <p class="aviso-orden">
                No hay servicios activos disponibles para agregar.
            </p>
        @else
            <p class="aviso-orden">
                La cotización está disponible para consulta.
            </p>
        @endif

        <div class="tabla-contenedor">
            <table class="tabla-clientes">
                <thead>
                    <tr>
                        <th scope="col">Servicio</th>
                        <th scope="col">Precio unitario</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Subtotal</th>

                        @if ($order->status === 'open')
                            <th scope="col">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse ($order->items as $item)
                        <tr>
                            <td>{{ $item->service_name }}</td>

                            <td>
                                ${{ number_format($item->unit_price_cents / 100, 2) }}
                            </td>

                            <td>{{ $item->quantity }}</td>

                            <td>
                                ${{ number_format($item->subtotalCents() / 100, 2) }}
                            </td>

                            @if ($order->status === 'open')
                                <td>
                                    <form
                                        class="editar-partida"
                                        method="POST"
                                        action="{{ route('work-orders.items.update', [$order, $item]) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <div class="campo-cliente">
                                            <label for="quantity-{{ $item->id }}">
                                                Nueva cantidad
                                            </label>

                                            <input
                                                id="quantity-{{ $item->id }}"
                                                name="quantity"
                                                type="number"
                                                min="1"
                                                max="32767"
                                                step="1"
                                                value="{{ $item->quantity }}"
                                                required
                                            >
                                        </div>

                                        <button type="submit" class="boton-cliente">
                                            Actualizar
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('work-orders.items.destroy', [$order, $item]) }}"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="boton-cliente boton-quitar"
                                        >
                                            Quitar
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ $order->status === 'open' ? 5 : 4 }}"
                                class="tabla-vacia"
                            >
                                Todavía no hay servicios cotizados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="total-orden">
            Total: ${{ number_format($totalCents / 100, 2) }} MXN
        </p>
    </section>
@endsection