@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de servicios | AutoService Pro</title>

    <style>
        body {
            margin: 0;
            background: #f2f5f2;
            color: #10251a;
            font-family: system-ui, sans-serif;
        }

        main {
            max-width: 880px;
            margin: 0 auto;
            padding: clamp(20px, 5vw, 48px);
        }

        a { color: #163e28; }

        .catalogo {
            overflow-x: auto;
            background: #fff;
            border: 1px solid #d7e0d8;
            border-radius: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 18px;
            border-bottom: 1px solid #e7ede8;
            text-align: left;
        }

        th { background: #eaf0ea; }
        tbody tr:last-child td { border-bottom: 0; }
    </style>
</head>
<body>
    <main>
        <a href="{{ route('dashboard') }}">Volver al inicio</a>
        <h1>Catálogo de servicios</h1>
        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        @can('manage-services')
            <p><a href="{{ route('services.create') }}">Nuevo servicio</a></p>
        @endcan

        <div class="catalogo">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Servicio</th>
                        <th scope="col">Precio base (MXN)</th>
                        <th scope="col">Estado</th>
                        @can('manage-services')
                            <th scope="col">Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->name }}</td>
                            <td>${{ number_format($servicio->base_price_cents / 100, 2) }}</td>
                            <td>{{ $servicio->is_active ? 'Activo' : 'Inactivo' }}</td>
                            @can('manage-services')
                                <td>
                                    <a href="{{ route('services.edit', $servicio) }}">Editar</a>

                                    <form method="POST" action="{{ route('services.update-status', $servicio) }}">
                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="is_active"
                                            value="{{ $servicio->is_active ? '0' : '1' }}">

                                        <button type="submit">
                                            {{ $servicio->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Todavía no hay servicios disponibles para esta cuenta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
@endsection