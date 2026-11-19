<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio | AutoService Pro</title>
</head>
<body>
    <main>
        <h1>AutoService Pro</h1>
        <p>Bienvenido, {{ auth()->user()->name }}.</p>
        <p>Rol: {{ auth()->user()->role }}</p>

        <a href="{{ route('services.index') }}">Ir al catalogo</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Cerrar sesión</button>
        
        </form>
    </main>
</body>
</html>