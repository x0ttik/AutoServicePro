<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar vehículo | AutoService Pro</title>
</head>
<body>
    <main>
        <h1>Registrar vehículo</h1>
        <p>Cliente: {{ $client->name }}</p>

        <form method="POST" action="{{ route('vehicles.store', $client) }}">
            @csrf

            <p>
                <label for="plate">Placa</label>
                <input id="plate" name="plate" value="{{ old('plate') }}" required>
                @error('plate') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <p>
                <label for="make">Marca</label>
                <input id="make" name="make" value="{{ old('make') }}" maxlength="80" required>
                @error('make') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <p>
                <label for="model">Modelo</label>
                <input id="model" name="model" value="{{ old('model') }}" maxlength="80" required>
                @error('model') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <p>
                <label for="model_year">Año</label>
                <input id="model_year" name="model_year" type="number"
                       value="{{ old('model_year') }}" min="1000" max="9999" required>
                @error('model_year') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <button type="submit">Guardar vehículo</button>
        </form>
    </main>
</body>
</html>