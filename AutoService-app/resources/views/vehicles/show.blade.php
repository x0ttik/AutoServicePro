<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha del vehículo | AutoService Pro</title>
</head>
<body>
    <main>
        <h1>{{ $vehicle->make }} {{ $vehicle->model }}</h1>
        <a href="{{ route('vehicles.edit', $vehicle) }}">Editar vehículo</a>

        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        <dl>
            <dt>Placa</dt>
            <dd>{{ $vehicle->plate }}</dd>

            <dt>Año</dt>
            <dd>{{ $vehicle->model_year }}</dd>

            <dt>Cliente</dt>
            <dd>{{ $vehicle->client->name }}</dd>
        </dl>
    </main>
</body>
</html>