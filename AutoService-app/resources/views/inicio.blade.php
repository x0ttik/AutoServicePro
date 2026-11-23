<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoServicePro</title>
</head>
<body>
    <h1> Bienvenido a {{ $nombreTaller }}</h1>

    <p> Sistema de administración de un taller automotriz</p>

    <h2> Secciones del sistema </h2>
    <ul>
        <li>
            <a href="{{ route('clients.index') }}">Clientes</a>
        </li>
        <li>Vehículos</li>
        <li>Ordenes de servicio</li>
    </ul>
</body>
</html>