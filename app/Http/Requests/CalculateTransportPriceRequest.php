<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateTransportPriceRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'addresses' => ['required', 'array', 'min:2'],
            'addresses.*.country' => ['required', 'string'],
            'addresses.*.zip' => ['required', 'string'],
            'addresses.*.city' => ['required', 'string'],
        ];
    }
}
