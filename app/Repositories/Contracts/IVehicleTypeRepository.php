<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface IVehicleTypeRepository
{
    public function getAll(): Collection;
}
