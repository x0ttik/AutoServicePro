<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | AutoServicePro</title>
</head>
<body>
    <h1>Clientes del taller</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Accion</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($clients as $client)
                <tr>
                    <td>{{ $client -> name }}</td>
                    <td>{{ $client -> phone }}</td>
                    <td><a href="{{ route('vehicles.create', $client) }}">Registrar vehículo</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="2"> No hay clientes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="/">Volver al inicio</a>
</body>
</html>