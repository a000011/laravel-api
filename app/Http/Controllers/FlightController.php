<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use Illuminate\Http\Request;
use App\Models\Flights;
use App\Http\Resources\FlightsResource;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    private $status;
    private $response;

    public function getFlight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string',
            'to' => 'required|string',
            'date1' => 'required|date',
            'date2' => 'date',
            'passengers' => 'required|Numeric',
        ]);
        $errors = $validator->errors();

        if ($errors->messages()) {
            $this->status = 422;
            $this->response = new ErrorResource($errors);
        }
        else {
            $flightsTo = [];
            $flightsBack = [];

            $bookings = Booking::where('date_from', '>=', $request->date1)
                ->where('date_back', '<=', $request->date2)
                ->get();

            foreach ($bookings as $book){
                $flightsTo[] = new FlightsResource($book->flightTo());
                $flightsBack[] = new FlightsResource($book->flightBack());
            }


            $this->status = 200;
            $this->response = [
                'data' => [
                    'flights_to' => $flightsTo,
                    'flights_back' => $flightsBack,
                ]
            ];
        }
        return response()->json($this->response, $this->status);
    }
}
