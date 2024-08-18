<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransportController;

Route::post('/calculate-price', [TransportController::class, 'calculatePrice']);

