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

        <section>
            <h2>Cotización</h2>
            <p>Todavía no hay servicios cotizados.</p>
        </section>
    </main>
</body>
</html>