<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AutoService Pro')</title>
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
</head>
<body>
    <div class="panel">
        <aside class="sidebar">
            <div class="marca">
                AutoService 
                <span>PRO</span>
            </div>

            <p class="menu-titulo">OPERACION</p>

            <nav class="menu" aria-label="Menu priincipal">
                <a 
                    href="{{ route('dashboard') }}"
                    class="{{ request() -> routeIs('dashboard') ? 'activo' : '' }}"
                    @if(request() -> routeIs('dashboard')) aria-current="page"
@endif
                >
                    Resumen
                </a>

                <a 
                    href="{{ route('clients.index') }}"
                    class="{{ request() -> routeIs('clients.*') ? 'activo' : '' }}"
                    @if(request() -> routeIs('clients.*')) aria-current="page"
@endif
                >
                Clientes
                </a>

                <a 
                    href="{{ route('services.index') }}"
                    class="{{ request() -> routeIs('services.*') ? 'activo' : '' }}"
                    @if(request() -> routeIs('services.*')) aria-current="page"
@endif             
                >
                Catalogo de servicios
                </a>
            </nav>

            <div class="cuenta">
                <div class="usuario">
                    <strong>{{ auth() -> user() -> name }}</strong>
                    <p>
                        {{ auth() -> user() -> role === 'admin'
                            ? 'Administrador'
                            : 'Usuario'}}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="cerrar-sesion">
                        Cerrar sesion
                    </button>
                </form>
            </div>
        </aside>

        <main class="contenido">
            @yield('content')
        </main>
    </div>
</body>
</html>