@extends('layouts.app')

@section('title', ($vehicle ? 'Editar vehículo' : 'Registrar vehículo') . ' | AutoService Pro')

@section('content')
    <h1>{{ $vehicle ? 'Editar vehículo' : 'Registrar vehículo' }}</h1>

    <p class="descripcion">
        Cliente: {{ $client->name }}
    </p>

    <section class="tarjeta">
        <form
            class="formulario-cliente"
            method="POST"
            action="{{ $vehicle
                ? route('vehicles.update', $vehicle)
                : route('vehicles.store', $client) }}"
        >
            @csrf

            @if ($vehicle)
                @method('PATCH')
            @endif

            <div class="campo-cliente">
                <label for="plate">Placa</label>

                <input
                    type="text"
                    id="plate"
                    name="plate"
                    value="{{ old('plate', $vehicle?->plate) }}"
                    maxlength="20"
                    required
                >

                @error('plate')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="make">Marca</label>

                <input
                    type="text"
                    id="make"
                    name="make"
                    value="{{ old('make', $vehicle?->make) }}"
                    maxlength="80"
                    required
                >

                @error('make')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="model">Modelo</label>

                <input
                    type="text"
                    id="model"
                    name="model"
                    value="{{ old('model', $vehicle?->model) }}"
                    maxlength="80"
                    required
                >

                @error('model')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <label for="model_year">Año</label>

                <input
                    type="number"
                    id="model_year"
                    name="model_year"
                    value="{{ old('model_year', $vehicle?->model_year) }}"
                    min="1000"
                    max="9999"
                    required
                >

                @error('model_year')
                    <p class="error-cliente" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="campo-cliente">
                <button
                    type="button"
                    id="search-models"
                    class="boton-cliente"
                    data-url="{{ route('vehicles.models.index') }}"
                >
                    Buscar modelos
                </button>

                <p id="model-search-status" role="status" aria-live="polite"></p>

                <div id="model-suggestions-group" hidden>
                    <label for="model-suggestions">Modelos encontrados</label>
                    <select id="model-suggestions">
                        <option value="">Selecciona un modelo</option>
                    </select>
                </div>
            </div>

            <div class="acciones-cliente">
                <button type="submit" class="boton-cliente">
                    {{ $vehicle ? 'Guardar cambios' : 'Guardar vehículo' }}
                </button>

                <a
                    href="{{ route('clients.show', ['cliente' => $client->id]) }}"
                    class="enlace-cliente"
                >
                    Volver a la ficha del cliente
                </a>
            </div>
        </form>
    </section>
<script>
    const makeInput = document.getElementById('make');
    const yearInput = document.getElementById('model_year');
    const modelInput = document.getElementById('model');
    const searchButton = document.getElementById('search-models');
    const searchStatus = document.getElementById('model-search-status');
    const suggestionsGroup = document.getElementById('model-suggestions-group');
    const suggestions = document.getElementById('model-suggestions');

    let pendingSearch = null;

    function clearSuggestions() {
        suggestions.replaceChildren(new Option('Selecciona un modelo', ''));
        suggestionsGroup.hidden = true;
    }

    function resetSearch() {
        pendingSearch?.abort();
        pendingSearch = null;
        searchButton.disabled = false;
        searchStatus.textContent = '';
        clearSuggestions();
    }

    makeInput.addEventListener('input', resetSearch);
    yearInput.addEventListener('input', resetSearch);

    suggestions.addEventListener('change', () => {
        if (suggestions.value) {
            modelInput.value = suggestions.value;
        }
    });

    searchButton.addEventListener('click', async () => {
        const make = makeInput.value.trim();
        const year = yearInput.value.trim();

        clearSuggestions();

        if (!make || !/^[0-9]{4}$/.test(year)) {
            searchStatus.textContent = 'Escribe una marca y un año de cuatro dígitos.';
            return;
        }

        const search = new AbortController();
        pendingSearch = search;
        searchButton.disabled = true;
        searchStatus.textContent = 'Buscando modelos…';

        const url = new URL(searchButton.dataset.url, window.location.origin);
        url.searchParams.set('make', make);
        url.searchParams.set('model_year', year);

        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                signal: search.signal,
            });

            const data = await response.json();

            if (pendingSearch !== search) return;

            if (!response.ok || !Array.isArray(data.models) || data.models.length === 0) {
                searchStatus.textContent = data.message ?? 'Puedes escribir el modelo manualmente.';
                return;
            }

            for (const model of data.models) {
                suggestions.add(new Option(model, model));
            }

            suggestionsGroup.hidden = false;
            searchStatus.textContent = 'Selecciona una sugerencia o escribe el modelo.';
        } catch {
            if (pendingSearch === search) {
                searchStatus.textContent = 'No se pudo consultar. Puedes escribir el modelo manualmente.';
            }
        } finally {
            if (pendingSearch === search) {
                pendingSearch = null;
                searchButton.disabled = false;
            }
        }
    });
</script>
@endsection