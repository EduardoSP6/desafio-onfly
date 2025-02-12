<?php

namespace App\Http\Requests\TravelOrders;

use Illuminate\Foundation\Http\FormRequest;

class CreateTravelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destination' => [
                'required',
                'string',
                'max:200',
            ],
            'departure_date' => [
                "required",
                "date_format:Y-m-d",
            ],
            'return_date' => [
                "required",
                "date_format:Y-m-d",
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'destination' => 'Destino',
            'departure_date' => 'Data da partida',
            'return_date' => 'Data do retorno',
        ];
    }
}
