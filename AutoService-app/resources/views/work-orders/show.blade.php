<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden #{{ $order->id }} | AutoService Pro</title>
</head>
<body>
    <main>
        <a href="{{ route('work-orders.index') }}">Ver todas las órdenes</a>
        <h1>Orden #{{ $order->id }}</h1>

        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        <dl>
            <dt>Cliente</dt>
            <dd>{{ $order->vehicle->client->name }}</dd>

            <dt>Vehículo</dt>
            <dd>{{ $order->vehicle->make }} {{ $order->vehicle->model }}
                · {{ $order->vehicle->plate }}</dd>

            <dt>Trabajo solicitado</dt>
            <dd>{{ $order->description }}</dd>

            <dt>Estado</dt>
            <dd>{{ $order->statusLabel() }}</dd>

            <dt>Abierta por</dt>
            <dd>{{ $order->creator->name }}</dd>

            <dt>Fecha de apertura</dt>
            <dd>{{ $order->created_at->timezone('America/Tijuana')->format('d/m/Y H:i') }}</dd>
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
        <p role="alert">{{ $error }}</p>
    @endforeach

    @if ($actionLabel !== null)
        <form method="POST" action="{{ route('work-orders.update-status', $order) }}">
            @csrf
            @method('PATCH')

            <input type="hidden" name="expected_status" value="{{ $order->status }}">
            <input type="hidden" name="target_status" value="{{ $nextStatus }}">

            <button type="submit">{{ $actionLabel }}</button>
        </form>
    @endif

    @if ($order->delivered_at)
        <p>
            <strong>Fecha de entrega</strong>
            {{ $order->delivered_at->timezone('America/Tijuana')->format('d/m/Y H:i') }}
        </p>
    @endif

        <section>
        <h2>Cotización</h2>

        @if ($errors->any())
            <ul role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if ($order->status === 'open' && $services->isNotEmpty())
            <form method="POST" action="{{ route('work-orders.items.store', $order) }}">
                @csrf

                <label for="service_id">Servicio</label>
                <select id="service_id" name="service_id" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }} · ${{ number_format($service->base_price_cents / 100, 2) }}
                        </option>
                    @endforeach
                </select>

                <label for="new_quantity">Cantidad</label>
                <input id="new_quantity" name="quantity" type="number"
                    min="1" max="32767" step="1" value="1" required>

                <button type="submit">Agregar servicio</button>
            </form>
        @elseif ($order->status === 'open')
            <p>No hay servicios activos disponibles para agregar.</p>
        @else
            <p>La cotización está disponible para consulta.</p>
        @endif

        <table>
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
                        <td>${{ number_format($item->unit_price_cents / 100, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->subtotalCents() / 100, 2) }}</td>

                        @if ($order->status === 'open')
                            <td>
                                <form method="POST"
                                    action="{{ route('work-orders.items.update', [$order, $item]) }}">
                                    @csrf
                                    @method('PATCH')

                                    <label for="quantity-{{ $item->id }}">Nueva cantidad</label>
                                    <input id="quantity-{{ $item->id }}" name="quantity"
                                        type="number" min="1" max="32767" step="1"
                                        value="{{ $item->quantity }}" required>

                                    <button type="submit">Actualizar</button>
                                </form>

                                <form method="POST"
                                    action="{{ route('work-orders.items.destroy', [$order, $item]) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">Quitar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $order->status === 'open' ? 5 : 4 }}">
                            Todavía no hay servicios cotizados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p><strong>Total: ${{ number_format($totalCents / 100, 2) }} MXN</strong></p>
    </section>
    </main>
</body>
</html>