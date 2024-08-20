<?php

namespace App\DTOs;
class CalculateTransportPriceDTO
{

    /**
     * This variable holds an array of addresses.
     * @var AddressDTO[] $addresses
     */
    private array $addresses;

    public function __construct(AddressDTO ...$addresses)
    {
        $this->addresses = $addresses;
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }
}
