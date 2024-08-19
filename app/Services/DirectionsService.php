<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DirectionsService
{
    /**
     * Calculate the distance between two points using the Google Maps Directions API.
     *
     * @param string $origin The starting point of the journey.
     * @param string $destination The destination point of the journey.
     * @return float The calculated distance in kilometers.
     */
    public function getDistanceBetweenPoints(string $origin, string $destination): float
    {
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
