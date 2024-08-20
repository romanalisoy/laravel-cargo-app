<?php

use App\Http\Middleware\ValidateToken;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransportController;

Route::middleware(ValidateToken::class)->post('/calculate-price', [TransportController::class, 'calculatePrice']);

