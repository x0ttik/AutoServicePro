@extends('layouts.app')

@section('title', ($service ? 'Editar servicio' : 'Nuevo servicio') . ' | AutoService Pro')

@section('content')
    <h1>{{ $service ? 'Editar servicio' : 'Nuevo servicio' }}</h1>
    <p class="descripcion">
        {{ $service ? 'Actualiza el nombre y precio base del servicio.' : 'Registra un servicio en el catálogo del taller.' }}
    </p>

    <section class="tarjeta">
        <form
            class="formulario-cliente"
            method="POST"
            action="{{ $service ? route('services.update', $service) : route('services.store') }}"
        >
            @csrf
            @if ($service)
                @method('PATCH')
            @endif

            <div class="campo-cliente">
                <label for="name">Nombre del servicio</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $service?->name) }}"
                    maxlength="120"
                    required
                >
                @error('name')
                    <p class="error-cliente" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="price">Precio base en MXN</label>
                <input
                    id="price"
                    name="price"
                    type="text"
                    inputmode="decimal"
                    value="{{ old('price', $service ? number_format($service->base_price_cents / 100, 2, '.', '') : '') }}"
                    aria-describedby="price-help"
                    required
                >
                <p id="price-help" class="ayuda-servicio">Usa punto decimal, por ejemplo 350.50.</p>
                @error('price')
                    <p class="error-cliente" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="acciones-cliente">
                <button type="submit" class="boton-cliente">
                    {{ $service ? 'Guardar cambios' : 'Guardar servicio' }}
                </button>
                <a href="{{ route('services.index') }}" class="enlace-cliente">Volver al catálogo</a>
            </div>
        </form>
    </section>
@endsection
