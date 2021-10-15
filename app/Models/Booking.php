<?php

namespace App\Models;

use App\Models\Flights;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public function flightTo(){
        return Flights::where('id', $this->flight_from)->first();
    }

    public function flightBack(){
        return Flights::where('id', $this->flight_back)->first();
    }

    protected $table = 'bookings';
}
