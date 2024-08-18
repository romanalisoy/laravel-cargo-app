<?php

namespace App\Services;

use App\DTOs\CalculateTransportPriceDTO;
use App\Models\City;
use App\Models\VehicleType;
use GuzzleHttp\Client;

class TransportPriceService
{
    public function calculatePrice(CalculateTransportPriceDTO $dto)
    {
        // Validate that each address exists in the database
        foreach ($dto->addresses as $address) {
            $cityExists = City::where('country', $address['country'])
                ->where('zipCode', $address['zip'])
                ->where('name', $address['city'])
                ->exists();

            if (!$cityExists) {
                throw new \Exception('Invalid address');
            }
        }

        // Calculate distance using Google Directions API
        $distance = $this->calculateDistance($dto->addresses);

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

    private function calculateDistance(array $addresses)
    {
        $client = new Client();
        $origin = $addresses[0]['city'] . ',' . $addresses[0]['country'];
        $destination = end($addresses)['city'] . ',' . end($addresses)['country'];

        $response = $client->get('https://maps.googleapis.com/maps/api/directions/json', [
            'query' => [
                'origin' => $origin,
                'destination' => $destination,
                'key' => env('GOOGLE_API_KEY'),
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $distanceInMeters = $data['routes'][0]['legs'][0]['distance']['value'];

        return $distanceInMeters / 1000; // Convert to kilometers
    }
}
