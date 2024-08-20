<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class DirectionsService
{
    /**
     * Calculate the distance between two points using the Google Maps Directions API.
     *
     * @param string $origin The starting point of the journey.
     * @param string $destination The destination point of the journey.
     * @return float The calculated distance in kilometers.
     * @throws Exception
     */
    public function getDistanceBetweenPoints(string $origin, string $destination): float
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
            'origin' => $origin,
            'destination' => $destination,
            'key' => config('services.google.maps.key'),
        ]);
        if ($response->failed()) {
            // Handle error, log it, or throw an exception
            throw new Exception('Failed to fetch directions');
        }

        $data = $response->json();
        if (empty($data['routes']) || empty($data['routes'][0]['legs'])) {
            // Handle invalid response or throw an exception
            throw new Exception('Invalid Google Maps API response');
        }
        $distanceInMeters = $data['routes'][0]['legs'][0]['distance']['value'];

        return $distanceInMeters / 1000; // Convert to kilometers
    }
}
