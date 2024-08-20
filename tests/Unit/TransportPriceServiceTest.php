<?php

namespace Tests\Unit;

use App\DTOs\AddressDTO;
use App\DTOs\CalculateTransportPriceDTO;
use App\Exceptions\AddressNotFoundException;
use App\Repositories\Contracts\ICityRepository;
use App\Repositories\Contracts\IVehicleTypeRepository;
use App\Services\DirectionsService;
use App\Services\TransportPriceService;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransportPriceServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCalculateTotalDistance()
    {
        $cityRepository = Mockery::mock(ICityRepository::class);
        $vehicleTypeRepository = Mockery::mock(IVehicleTypeRepository::class);
        $directionsService = Mockery::mock(DirectionsService::class);

        $transportPriceService = new TransportPriceService(
            $cityRepository,
            $vehicleTypeRepository,
            $directionsService
        );

        $inputs = [
            (object)['city' => 'Hamburg', 'country' => 'DE'],
            (object)['city' => 'Munich', 'country' => 'DE'],
            (object)['city' => 'Berlin', 'country' => 'DE']
        ];

        $directionsService->shouldReceive('getDistanceBetweenPoints')
            ->withArgs(['Hamburg,DE', 'Munich,DE'])
            ->andReturn(793.14);

        $directionsService->shouldReceive('getDistanceBetweenPoints')
            ->withArgs(['Munich,DE', 'Berlin,DE'])
            ->andReturn(585.28);

        $distance = $transportPriceService->calculateTotalDistance($inputs);

        $this->assertEquals(1378.42, $distance);
    }

    public function testCalculateTotalDistanceWithSingleInput()
    {
        $cityRepository = Mockery::mock(ICityRepository::class);
        $vehicleTypeRepository = Mockery::mock(IVehicleTypeRepository::class);
        $directionsService = Mockery::mock(DirectionsService::class);

        $transportPriceService = new TransportPriceService(
            $cityRepository,
            $vehicleTypeRepository,
            $directionsService
        );

        $inputs = [(object)['city' => 'Berlin', 'country' => 'DE']];

        $distance = $transportPriceService->calculateTotalDistance($inputs);

        $this->assertEquals(0.0, $distance);
    }


    /**
     * @throws AddressNotFoundException
     * @throws Exception
     */
    public function testCalculatePrice(): void
    {
        $cityRepository = Mockery::mock(ICityRepository::class);
        $vehicleTypeRepository = Mockery::mock(IVehicleTypeRepository::class);
        $directionsService = Mockery::mock(DirectionsService::class);
        $transportPriceService = new TransportPriceService(
            $cityRepository,
            $vehicleTypeRepository,
            $directionsService
        );

        $address1 = new AddressDTO('DE', '20095', 'Hamburg');
        $address2 = new AddressDTO('DE', '80331', 'Munich');
        $address3 = new AddressDTO('DE', '10115', 'Berlin');
        $dto = new CalculateTransportPriceDTO($address1, $address2, $address3);

        $cityRepository->expects()
            ->findBy('DE', '20095', 'Hamburg')
            ->andReturn((object)['name' => 'Hamburg']);

        $cityRepository->expects()
            ->findBy('DE', '80331', 'Munich')
            ->andReturn((object)['name' => 'Munich']);

        $cityRepository->expects()
            ->findBy('DE', '10115', 'Berlin')
            ->andReturn((object)['name' => 'Berlin']);

        $directionsService->expects()
            ->getDistanceBetweenPoints('Hamburg,DE', 'Munich,DE')
            ->andReturn(793.14);

        $directionsService->expects()
            ->getDistanceBetweenPoints('Munich,DE', 'Berlin,DE')
            ->andReturn(585.28);

        $vehicleTypeRepository->expects()->getAll()->andReturn(collect([
            (object)[
                'number' => 3,
                'cost_km' => 0.26,
                'minimum' => 169
            ],
            (object)[
                'number' => 10,
                'cost_km' => 0.28,
                'minimum' => 399
            ]
        ]));

        $prices = $transportPriceService->calculatePrice($dto);

        $this->assertEquals([
            [
                'vehicle_type' => 3,
                'price' => 358.39
            ],
            [
                'vehicle_type' => 10,
                'price' => 399
            ]
        ], $prices);
    }
}
