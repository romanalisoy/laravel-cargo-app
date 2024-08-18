<?php

namespace App\Services;

use App\DTOs\CalculateTransportPriceDTO;
use App\Models\City;
use App\Models\VehicleType;
use Exception;
use HttpException;
use Illuminate\Support\Facades\Http;

class TransportPriceService
{
    /**
     * Calculate the transport price based on the input data.
     *
     * @param CalculateTransportPriceDTO $dto The data transfer object containing the input data.
     * @return array The calculated prices.
     * @throws Exception Thrown when an error occurs.
     */
    public function calculatePrice(CalculateTransportPriceDTO $dto): array
    {
        // Validate that each address exists in the database
        foreach ($dto->getAddresses() as $address) {
            $cityExists = City::query()->where('country', $address['country'])
                ->where('zipCode', $address['zip'])
                ->where('name', $address['city'])
                ->exists();

            if (!$cityExists) {
                throw new Exception('Invalid address');
            }
        }

        // Calculate distance using Google Directions API
        $distance = $this->calculateDistance($dto->getAddresses());

        // Get vehicle types and calculate price
        $vehicleTypes = VehicleType::all();
        $prices = [];

        foreach ($vehicleTypes as $vehicleType) {
            $price = $distance * $vehicleType->cost_km;
            if ($price < $vehicleType->minimum) {
                $price = $vehicleType->minimum;
            }
            $prices[] = [
                'vehicle_type' => $vehicleType->number,
                'price' => $price,
            ];
        }

        return $prices;
    }

    /**
     * @throws Exception
     */
    private function calculateDistance(array $addresses)
    {
        $origin = $addresses[0]['city'] . ',' . $addresses[0]['country'];
        $destination = end($addresses)['city'] . ',' . end($addresses)['country'];

        $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
            'origin' => $origin,
            'destination' => $destination,
            'key' => env('GOOGLE_API_KEY'),

        ]);

        $data = $response->json();
        $distanceInMeters = $data['routes'][0]['legs'][0]['distance']['value'];

        return $distanceInMeters / 1000; // Convert to kilometers

    }
}
