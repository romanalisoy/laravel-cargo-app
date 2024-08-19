<?php

namespace App\Repositories\Eloquent;

use App\Models\VehicleType;
use App\Repositories\Contracts\IVehicleTypeRepository;
use Illuminate\Support\Collection;

class VehicleTypeRepository implements IVehicleTypeRepository
{

    /**
     * Get all vehicle types
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return VehicleType::all();
    }
}
