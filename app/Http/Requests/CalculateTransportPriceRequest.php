<?php

namespace App\Http\Requests;

use App\DTOs\CalculateTransportPriceDTO;
use Illuminate\Foundation\Http\FormRequest;

class CalculateTransportPriceRequest extends FormRequest
{
    /**
     * @return true
     */
    public function authorize(): true
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'addresses' => ['required', 'array', 'min:2'],
            'addresses.*.country' => ['required', 'string'],
            'addresses.*.zip' => ['required', 'string'],
            'addresses.*.city' => ['required', 'string'],
        ];
    }

    /**
     * Converts the current object to a CalculateTransportPriceDTO object.
     *
     * @return CalculateTransportPriceDTO The CalculateTransportPriceDTO representing the current object.
     */
    public function toDto(): CalculateTransportPriceDTO
    {
        return new CalculateTransportPriceDTO($this);
    }
}
