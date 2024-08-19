<?php

namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
use App\Services\TransportPriceService;
use App\DTOs\CalculateTransportPriceDTO;
use App\Repositories\Contracts\ICityRepository;
use App\Repositories\Contracts\IVehicleTypeRepository;
use App\Services\DirectionsService;
use Exception;

class TransportPriceServiceTest extends TestCase
{
    private $cityRepository;
    private $vehicleTypeRepository;
    private $directionsService;
    private $transportPriceService;

    protected function setUp(): void
    {
        $this->cityRepository = $this->createMock(ICityRepository::class);
        $this->vehicleTypeRepository = $this->createMock(IVehicleTypeRepository::class);
        $this->directionsService = $this->createMock(DirectionsService::class);
        $this->transportPriceService = new TransportPriceService(
            $this->cityRepository,
            $this->vehicleTypeRepository,
            $this->directionsService
        );
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function calculatePriceReturnsCorrectPrices(): void
    {
        $dto = $this->createMock(CalculateTransportPriceDTO::class);
        $dto->method('getAddresses')->willReturn([
            ['country' => 'DE', 'zip' => '20095', 'city' => 'Hamburg'],
            ['country' => 'DE', 'zip' => '10115', 'city' => 'Berlin'],
        ]);

        $this->cityRepository->method('findBy')->willReturn(true);
        $this->directionsService->method('getDistanceBetweenPoints')->willReturn(288.548);

        $prices = $this->transportPriceService->calculatePrice($dto);

        $this->assertCount(2, $prices);
        $this->assertEquals(200, $prices[0]['price']);
        $this->assertEquals(300, $prices[1]['price']);
    }

    public function calculatePriceThrowsExceptionForInvalidAddress()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid address');

        $dto = $this->createMock(CalculateTransportPriceDTO::class);
        $dto->method('getAddresses')->willReturn([
            ['country' => 'DE', 'zip' => '10115', 'city' => 'Berlin'],
        ]);

        $this->cityRepository->method('findBy')->willReturn(false);

        $this->transportPriceService->calculatePrice($dto);
    }

    public function calculateTotalDistanceReturnsCorrectDistance(): void
    {
        $addresses = [
            ['country' => 'DE', 'city' => 'Berlin'],
            ['country' => 'DE', 'city' => 'Hamburg'],
            ['country' => 'DE', 'city' => 'Munich']
        ];

        $this->directionsService->method('getDistanceBetweenPoints')
            ->willReturnOnConsecutiveCalls(100, 200);

        $distance = $this->transportPriceService->calculateTotalDistance($addresses);

        $this->assertEquals(300, $distance);
    }
}
