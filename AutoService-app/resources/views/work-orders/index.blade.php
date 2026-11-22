<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes de trabajo | AutoService Pro</title>
</head>
<body>
    <main>
        <a href="{{ route('dashboard') }}">Volver al inicio</a>
        <h1>Órdenes de trabajo</h1>

        @if (session('error'))
            <p role="alert">{{ session('error') }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th scope="col">Folio</th>
                    <th scope="col">Vehículo</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Apertura</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('work-orders.show', $order) }}">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>{{ $order->vehicle->make }} {{ $order->vehicle->model }}
                            · {{ $order->vehicle->plate }}</td>
                        <td>{{ $order->statusLabel() }}</td>
                        <td>{{ $order->created_at->timezone('America/Tijuana')->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Todavía no hay órdenes registradas.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $orders->links() }}
    </main>
</body>
</html>