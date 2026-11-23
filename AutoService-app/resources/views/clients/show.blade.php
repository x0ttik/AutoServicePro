@extends('layouts.app')

@section('title', 'Ficha del cliente | AutoService Pro')

@section('content')
    <h1>Ficha del cliente</h1>

    <p class="descripcion">
        Consulta los datos registrados del cliente.
    </p>

    @if (session('success'))
        <p class="exito-cliente" role="status">
            {{ session('success') }}
        </p>
    @endif

    <section class="tarjeta" aria-labelledby="titulo-ficha">
        <h2 id="titulo-ficha">{{ $cliente->name }}</h2>

        <dl class="ficha-cliente">
            <dt>Nombre completo</dt>
            <dd>{{ $cliente->name }}</dd>

            <dt>Teléfono</dt>
            <dd>{{ $cliente->phone }}</dd>
        </dl>

    <div class="acciones-cliente">
        <a
            href="{{ route('clients.edit', ['cliente' => $cliente->id]) }}"
            class="boton-cliente"
        >
            Editar cliente
        </a>

        <a
            href="{{ route('clients.index') }}"
            class="enlace-cliente"
        >
            Volver al listado
        </a>
    </div>
    </section>
@endsection