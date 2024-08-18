<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateTransportPriceRequest;
use App\DTOs\CalculateTransportPriceDTO;
use App\Services\TransportPriceService;
use Illuminate\Http\JsonResponse;

class TransportController extends Controller
{
    protected TransportPriceService $transportPriceService;

    /**
     * Class constructor.
     *
     * @param TransportPriceService $transportPriceService The transport price service.
     */
    public function __construct(TransportPriceService $transportPriceService)
    {
        $this->transportPriceService = $transportPriceService;
    }

    /**
     * Calculate the transport price based on the request input.
     *
     * @param CalculateTransportPriceRequest $request The request object containing the input data.
     * @return JsonResponse The JSON response containing the calculated prices.
     */
    public function calculatePrice(CalculateTransportPriceRequest $request): JsonResponse
    {
        try {
            $prices = $this->transportPriceService->calculatePrice($request->toDto());
            return response()->json($prices);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
