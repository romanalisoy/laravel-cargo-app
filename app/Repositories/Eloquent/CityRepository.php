<?php

namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Repositories\Contracts\ICityRepository;
use stdClass;

class CityRepository implements ICityRepository
{

    /**
     * Find a city by country, zip code, and name.
     *
     * @param string $country The country of the city.
     * @param string $zip The zip code of the city.
     * @param string $city The name of the city.
     * @return stdClass|City|null The found city, or null if not found.
     */
    public function findBy(string $country, string $zip, string $city): City|null|stdClass
    {
        return City::query()->where('country', $country)
            ->where('zipCode', $zip)
            ->where('name', $city)
            ->first();
    }
}
