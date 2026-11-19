<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo servicio | AutoService Pro</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5f2; color: #10251a; font-family: system-ui, sans-serif; }
        main { max-width: 580px; margin: 0 auto; padding: 32px 20px; }
        a { color: #163e28; }
        form { padding: 24px; border: 1px solid #d7e0d8; border-radius: 14px; background: white; }
        label { display: block; margin: 0 0 6px; font-weight: 600; }
        input { width: 100%; padding: 10px; border: 1px solid #a6b6a9; border-radius: 8px; font: inherit; }
        .campo { margin-bottom: 18px; }
        .ayuda { margin: 6px 0 0; font-size: 14px; }
        .error { margin: 6px 0 0; color: #a32727; }
        button { padding: 10px 16px; border: 0; border-radius: 8px; background: #163e28; color: white; font: inherit; cursor: pointer; }
    </style>
</head>
<body>
    <main>
        <a href="{{ route('services.index') }}">Volver al catálogo</a>
        <h1>Nuevo servicio</h1>

        <form method="POST" action="{{ route('services.store') }}">
            @csrf

            <div class="campo">
                <label for="name">Nombre del servicio</label>
                <input id="name" name="name" type="text"
                       value="{{ old('name') }}" maxlength="120" required>
                @error('name')
                    <p class="error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="campo">
                <label for="price">Precio base en MXN</label>
                <input id="price" name="price" type="text" inputmode="decimal"
                       value="{{ old('price') }}" aria-describedby="price-help" required>
                <p id="price-help" class="ayuda">Usa punto decimal, por ejemplo 350.50.</p>
                @error('price')
                    <p class="error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Guardar servicio</button>
        </form>
    </main>
</body>
</html>