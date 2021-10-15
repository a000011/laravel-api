<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\FlightController;


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/airport', [AirportController::class, 'getAirport']);
Route::get('/flight', [FlightController::class, 'getFlight']);
