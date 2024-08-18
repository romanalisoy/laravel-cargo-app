<?php

namespace App\DTOs;

use App\Http\Requests\CalculateTransportPriceRequest;

class CalculateTransportPriceDTO
{
    private array $addresses;

    public function __construct(CalculateTransportPriceRequest $request)
    {
        $this->addresses = $request->input('addresses');
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }
}
