@extends('layouts.app')

@section('title', 'Editar cliente | AutoService Pro')

@section('content')
    <h1>Editar cliente</h1>

    <p class="descripcion">
        Modifica el nombre o teléfono del cliente.
    </p>

    <section class="tarjeta">
        <form
            class="formulario-cliente"
            method="POST"
            action="{{ route('clientes.update', ['cliente' => $cliente->id]) }}"
        >
            @csrf
            @method('PUT')

            <div class="campo-cliente">
                <label for="name">Nombre completo</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $cliente->name) }}"
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
                    value="{{ old('phone', $cliente->phone) }}"
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
                    Guardar cambios
                </button>

                <a
                    href="{{ route('clientes.show', ['cliente' => $cliente->id]) }}"
                    class="enlace-cliente"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </section>
@endsection