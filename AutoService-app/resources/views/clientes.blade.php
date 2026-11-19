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
            </tr>
        </thead>

        <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente -> name }}</td>
                    <td>{{ $cliente -> phone }}</td>
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