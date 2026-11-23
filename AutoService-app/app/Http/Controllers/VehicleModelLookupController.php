<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VehicleModelLookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'make' => ['required', 'string', 'max:80', 'regex:/[\p{L}\p{N}]/u'],
            'model_year' => ['required', 'integer', 'between:1000,9999'],
        ], [
            'make.required' => 'Escribe la marca para buscar modelos.',
            'make.regex' => 'Escribe una marca válida.',
            'model_year.required' => 'Escribe el año para buscar modelos.',
            'model_year.integer' => 'Escribe el año como número entero.',
            'model_year.between' => 'Escribe un año de cuatro dígitos.',
        ]);

        $year = (int) $data['model_year'];

        if ($year <= 1995) {
            return response()->json([
                'models' => [],
                'message' => 'La búsqueda está disponible desde 1996. Escribe el modelo manualmente.',
            ]);
        }

        $make = rawurlencode(trim($data['make']));
        $url = "https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/{$make}/modelyear/{$year}";

        try {
            $response = Http::acceptJson()
                ->connectTimeout(3)
                ->timeout(6)
                ->get($url, ['format' => 'json']);
        } catch (ConnectionException) {
            return $this->unavailable();
        }

        $results = $response->json('Results');

        if (! $response->successful() || ! is_array($results)) {
            return $this->unavailable();
        }

        $models = collect($results)
            ->pluck('Model_Name')
            ->filter(fn ($model) => is_string($model) && trim($model) !== '')
            ->map(fn ($model) => trim($model))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return response()->json([
            'models' => $models,
            'message' => $models === []
                ? 'No se encontraron modelos. Puedes escribir el modelo manualmente.'
                : null,
        ]);
    }

    private function unavailable(): JsonResponse
    {
        return response()->json([
            'models' => [],
            'message' => 'La consulta de modelos no está disponible. Puedes escribir el modelo manualmente.',
        ], 503);
    }
}