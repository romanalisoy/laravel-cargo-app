<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransportController;

Route::middleware('auth:sanctum')->post('/calculate-price', [TransportController::class, 'calculatePrice']);

