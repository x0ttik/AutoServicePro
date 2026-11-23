<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AutoService Pro')</title>
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @stack('styles')
</head>
<body>
    <div class="panel">
        <aside class="sidebar">
            <div class="marca">
                AutoService <span>PRO</span>
            </div>

            <p class="menu-titulo">OPERACIÓN</p>

            <nav class="menu" aria-label="Menú principal">
                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'activo' : '' }}"
                    @if (request()->routeIs('dashboard')) aria-current="page" @endif
                >
                    Resumen
                </a>

                <a
                    href="{{ route('work-orders.index') }}"
                    class="{{ request()->routeIs('work-orders.*') ? 'activo' : '' }}"
                    @if (request()->routeIs('work-orders.index')) aria-current="page" @endif
                >
                    Órdenes de trabajo
                </a>

                <a
                    href="{{ route('clients.index') }}"
                    class="{{ request()->routeIs('clients.*', 'vehicles.*') ? 'activo' : '' }}"
                    @if (request()->routeIs('clients.index')) aria-current="page" @endif
                >
                    Clientes y vehículos
                </a>

                <a
                    href="{{ route('services.index') }}"
                    class="{{ request()->routeIs('services.*') ? 'activo' : '' }}"
                    @if (request()->routeIs('services.index')) aria-current="page" @endif
                >
                    Catálogo de servicios
                </a>
            </nav>

            <div class="cuenta">
                <div class="usuario">
                    <strong>{{ auth()->user()->name }}</strong>
                    <p>{{ auth()->user()->role === 'admin' ? 'Administrador' : 'Usuario' }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="cerrar-sesion">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <main class="contenido">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
