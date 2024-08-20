<?php

namespace Tests\Unit;

use App\Services\DirectionsService;
use Exception;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DirectionsServiceTest extends TestCase
{
    protected DirectionsService $directionsService;

    public function setUp(): void
    {
        parent::setUp();
        $this->directionsService = new DirectionsService();
    }

    /**
     * Test that the `getDistanceBetweenPoints` method calculates the distance
     * between two points correctly.
     *
     * @return void
     *
     * @throws Exception
     */
    public function testGetDistanceBetweenPointsCalculatesDistanceCorrectly()
    {
        Http::fake([
            '*' => Http::response([
                'routes' => [
                    [
                        'legs' => [
                            [
                                'distance' => [
                                    'value' => 5000, // 5 kilometers
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $distance = $this->directionsService->getDistanceBetweenPoints('origin', 'destination');

        $this->assertEquals(5.0, $distance);
    }

    /**
     * Test that the `getDistanceBetweenPoints` method throws an Exception
     * when the Google Maps API request fails.
     *
     * @return void
     *
     * @throws Exception
     */
    public function testGetDistanceBetweenPointsThrowsExceptionWhenApiFails()
    {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to fetch directions');

        $this->directionsService->getDistanceBetweenPoints('origin', 'destination');
    }

    /**
     * Test that the `getDistanceBetweenPoints` method throws an Exception
     * when the Google Maps API response is invalid.
     *
     * @return void
     *
     * @throws Exception
     */
    public function testGetDistanceBetweenPointsThrowsExceptionOnInvalidApiResponse()
    {
        Http::fake([
            '*' => Http::response([], 200),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid Google Maps API response');

        $this->directionsService->getDistanceBetweenPoints('origin', 'destination');
    }
}
