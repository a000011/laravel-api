<?php

use App\Http\Controllers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Airport;


Route::post('/register', [User::class, 'register']);
Route::post('/login', [User::class, 'login']);
Route::get('/airport', [Airport::class, 'getAirport']);
