<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flights;
use App\Http\Resources\FlightsResource;
use App\Models\Booking;

class FlightController extends Controller
{
    public function getFlight(Request $request)
    {
        $flightsTo = [];
        $flightsBack = [];

        $bookings = Booking::where('date_from', '>=', $request->date1)
            ->where('date_back', '<=', $request->date2)
            ->get();

        foreach ($bookings as $book){
            $flightsTo[] = new FlightsResource($book->flightTo());
            $flightsBack[] = new FlightsResource($book->flightBack());
        }

        return response()->json([
            'data' => [
                'flights_to' => $flightsTo,
                'flights_back' => $flightsBack,
            ]
        ]);
    }
}
