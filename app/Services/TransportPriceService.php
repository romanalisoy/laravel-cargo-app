<?php

namespace App\Services;

use App\DTOs\CalculateTransportPriceDTO;
use App\Exceptions\AddressNotFoundException;
use App\Repositories\Contracts\ICityRepository;
use App\Repositories\Contracts\IVehicleTypeRepository;
use Exception;

class TransportPriceService
{
    protected ICityRepository $cityRepository;
    protected IVehicleTypeRepository $vehicleTypeRepository;
    private DirectionsService $directionsService;

    public function __construct(ICityRepository $cityRepository, IVehicleTypeRepository $vehicleTypeRepository, DirectionsService $directionsService)
    {
        $this->directionsService = $directionsService;
        $this->cityRepository = $cityRepository;
        $this->vehicleTypeRepository = $vehicleTypeRepository;
    }

    /**
     * Calculate the transport price based on the input data.
     *
     * @param CalculateTransportPriceDTO $dto The data transfer object containing the input data.
     * @return array The calculated prices.
     * @throws AddressNotFoundException
     * @throws Exception
     */
    public function calculatePrice(CalculateTransportPriceDTO $dto): array
    {
        // Validate that each address exists in the database
        foreach ($dto->getAddresses() as $address) {
            $city = $this->cityRepository->findBy(
                $address->country,
                $address->zip,
                $address->city
            );
            if (!$city) {
                throw new AddressNotFoundException('Invalid address');
            }
        }
        // Calculate distance using Google Directions API
        $distance = $this->calculateTotalDistance($dto->getAddresses());

        // Get vehicle types form mongodb and calculate price
        $vehicleTypes = $this->vehicleTypeRepository->getAll();
        $prices = [];

        foreach ($vehicleTypes as $vehicleType) {
            $prices[] = [
                'vehicle_type' => $vehicleType->number,
                'price' => round(min($distance * $vehicleType->cost_km, $vehicleType->minimum), 2),
            ];
        }

        return $prices;
    }

    /**
     * Calculate the total distance between a list of addresses.
     *
     * @param array $addresses An array of addresses.
     * @return float The total distance.
     * @throws Exception
     */
    public function calculateTotalDistance(array $addresses): float
    {
        $totalDistance = 0;

        // Calculate distance between each consecutive pair of addresses
        for ($i = 0; $i < count($addresses) - 1; $i++) {
            $distance = $this->directionsService->getDistanceBetweenPoints(
                origin: $addresses[$i]->city . ',' . $addresses[$i]->country,
                destination: $addresses[$i + 1]->city . ',' . $addresses[$i + 1]->country);
            $totalDistance += $distance;
        }
        return $totalDistance;
    }
}
