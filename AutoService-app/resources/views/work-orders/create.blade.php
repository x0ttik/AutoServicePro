<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abrir orden | AutoService Pro</title>
</head>
<body>
    <main>
        <h1>Abrir orden de trabajo</h1>
        <p>Cliente: {{ $vehicle->client->name }}</p>
        <p>Vehículo: {{ $vehicle->make }} {{ $vehicle->model }}
            · {{ $vehicle->plate }}</p>

        <form method="POST" action="{{ route('work-orders.store', $vehicle) }}">
            @csrf

            <label for="description">Trabajo solicitado</label>
            <textarea id="description" name="description"
                      rows="5" required>{{ old('description') }}</textarea>

            @error('description')
                <p role="alert">{{ $message }}</p>
            @enderror

            <button type="submit">Abrir orden</button>
        </form>

        <p><a href="{{ route('vehicles.show', $vehicle) }}">Volver al vehículo</a></p>
    </main>
</body>
</html>