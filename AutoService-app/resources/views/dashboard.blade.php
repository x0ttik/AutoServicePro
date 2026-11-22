@extends('layouts.app')

@section('title', 'Resumen | AutoService Pro')

@section('content')
    <h1> Bienvenido, {{ auth() -> user() -> name }}</h1>

    <p class="descripcion"> 
        Administra los clientes y el catalogo de servicios del taller
    </p>

    <section>
        <h2>AutoService Pro</h2>

        <p class="tarjeta">
            Selecciona un modulo en el menu lateral para comenzar o 
            <a href="{{ route('work-orders.index') }}">Órdenes de trabajo</a>
        </p>
    </section>
@endsection