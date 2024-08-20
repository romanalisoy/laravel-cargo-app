<?php

namespace App\DTOs;

class AddressDTO
{
    public function __construct(public string $country, public string $zip, public string $city)
    {
    }
}
