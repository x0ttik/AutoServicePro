<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Vehicle;

class SaveVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $plate = $this->input('plate');

        if (is_string($plate)) {
            $this->merge([
                'plate' => Str::upper(preg_replace('/\s+/u', '', $plate) ?? $plate),
            ]);
        }
    }

    public function rules(): array
    {
        $placaUnica = Rule::unique('vehicles', 'plate');
        $vehicle = $this->route('vehicle');
        //dd($vehicle->toArray());

        if ($vehicle instanceof Vehicle) {
            $placaUnica->ignore($vehicle);
        }

        return [
            'plate' => ['bail', 'required', 'string', 'max:20', $placaUnica],
            'make' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'model_year' => ['required', 'integer', 'between:1000,9999'],
        ];
    }

    public function messages(): array
    {
        return [
            'plate.required' => 'Escribe la placa del vehículo.',
            'plate.unique' => 'Ya existe un vehículo con esa placa.',
            'plate.max' => 'La placa puede tener hasta 20 caracteres.',
            'make.required' => 'Escribe la marca del vehículo.',
            'model.required' => 'Escribe el modelo del vehículo.',
            'model_year.required' => 'Escribe el año del vehículo.',
            'model_year.integer' => 'Escribe el año como un número entero.',
            'model_year.between' => 'Escribe un año de cuatro dígitos.',
        ];
    }
}