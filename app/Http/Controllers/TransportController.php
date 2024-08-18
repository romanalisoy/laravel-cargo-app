<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateTransportPriceRequest;
use App\DTOs\CalculateTransportPriceDTO;
use App\Services\TransportPriceService;
use Illuminate\Http\JsonResponse;

class TransportController extends Controller
{
    protected TransportPriceService $transportPriceService;

    public function __construct(TransportPriceService $transportPriceService)
    {
        $this->transportPriceService = $transportPriceService;
    }

    public function calculatePrice(CalculateTransportPriceRequest $request): JsonResponse
    {
        $dto = CalculateTransportPriceDTO::fromRequest($request);
        try {
            $prices = $this->transportPriceService->calculatePrice($dto);
            return response()->json($prices);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
