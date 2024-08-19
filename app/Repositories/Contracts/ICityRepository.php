<?php

namespace App\Repositories\Contracts;

use App\Models\City;

interface ICityRepository
{
    public function findBy(string $country, string $zip, string $city): ?City;

}
