<?php

namespace App\DTOs;

class CalculateTransportPriceDTO
{
    public array $addresses;

    public function __construct(array $addresses)
    {
        $this->addresses = $addresses;
    }

    public static function fromRequest($request): CalculateTransportPriceDTO
    {
        return new self(
            $request->addresses
        );
    }
}
