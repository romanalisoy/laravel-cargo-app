<?php

namespace App\Repositories\Contracts;

use App\Models\City;
use stdClass;

interface ICityRepository
{
    public function findBy(string $country, string $zip, string $city): City|stdClass|null;

}
