<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vehicle ? 'Editar vehículo' : 'Registrar vehículo' }} | AutoService Pro</title>
</head>
<body>
    <main>
        <h1>{{ $vehicle ? 'Editar vehículo' : 'Registrar vehículo' }}</h1>
        <p>Cliente: {{ $client->name }}</p>

        <form method="POST"
            action="{{ $vehicle ? route('vehicles.update', $vehicle) : route('vehicles.store', $client) }}">
            @csrf

            @if ($vehicle)
                @method('PATCH')
            @endif

            <p>
                <label for="plate">Placa</label>
                <input id="plate" name="plate"
                    value="{{ old('plate', $vehicle?->plate) }}" required>
                @error('plate') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <p>
                <label for="make">Marca</label>
                <input id="make" name="make"
                    value="{{ old('make', $vehicle?->make) }}" maxlength="80" required>
                @error('make') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <p>
                <label for="model">Modelo</label>
                <input id="model" name="model"
                    value="{{ old('model', $vehicle?->model) }}" maxlength="80" required>
                @error('model') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <p>
                <label for="model_year">Año</label>
                <input id="model_year" name="model_year" type="number"
                    value="{{ old('model_year', $vehicle?->model_year) }}"
                    min="1000" max="9999" required>
                @error('model_year') <span role="alert">{{ $message }}</span> @enderror
            </p>

            <button type="submit">{{ $vehicle ? 'Guardar cambios' : 'Guardar vehículo' }}</button>
        </form>
    </main>
</body>
</html>