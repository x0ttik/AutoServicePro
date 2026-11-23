@extends('layouts.app')

@section('title', 'Registrar cliente | AutoService Pro')

@section('content')
    <h1>Registrar cliente</h1>

    <p class="descripcion">
        Ingresa los datos del cliente para crear su ficha.
    </p>

    <section class="tarjeta">
        <form
            class="formulario-cliente"
            method="POST"
            action="{{ route('clients.store') }}"
        >
            @csrf

            <div class="campo-cliente">
                <label for="name">Nombre completo</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    maxlength="150"
                    required
                >

                @error('name')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="phone">Teléfono</label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    autocomplete="tel"
                    maxlength="20"
                    required
                >

                @error('phone')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="acciones-cliente">
                <button type="submit" class="boton-cliente">
                    Guardar cliente
                </button>

                <a
                    href="{{ route('clients.index') }}"
                    class="enlace-cliente"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </section>
@endsection