<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ErrorResource;

class BookingContoller extends Controller
{
    private $status = 200;
    private $response = null;

    public function createBooking(Request $request)
    {
        $errors = [];
        foreach ($request->passengers as $pass) {
            $validator = Validator::make($pass, [
                'first_name' => 'required',
                'last_name' => 'required',
                'birth_date' => 'required|date',
                'document_number' => 'required|string|size:10',
            ]);
            if ($validator->errors()) {
                $errors = array_merge(
                    $errors,
                    $validator->errors()->messages()
                );
            }
        }

        if($errors){
            $this->response = new ErrorResource((object)[
                'messages' => $errors
            ]);
        }
        else {
            $booking = Booking::where('flight_from', $request->flight_from['id'])
                ->where('date_from', $request->flight_from['date'])
                ->where('flight_back', $request->flight_back['id'])
                ->where('date_back', $request->flight_back['date'])
                ->first();

            foreach ($request->passengers as $pass){
                $passenger = new Passenger();
                $passenger->booking_id = $booking->id;
                $passenger->first_name = $pass['first_name'];
                $passenger->last_name = $pass['last_name'];
                $passenger->birth_date = $pass['birth_date'];
                $passenger->document_number = $pass['document_number'];
                $passenger->save();
            }

            $this->status = 201;
            $this->response = [
                'data' => [
                    'code' => $booking->code
                ]
            ];
        }

        return response()->json($this->response, $this->status);
    }

    public function getBooking($code)
    {
        $booking = Booking::where('code', $code)->first();
        $this->response = new BookingResource($booking);

        return response()->json($this->response, $this->status);
    }

    public function getEngagedSeats($code)
    {
        $booking = Booking::where('code', $code)->first();
        $passengers = Passenger::where('booking_id', $booking->id)->get();

        $engagedSeatsFrom = [];
        $engagedSeatsBack = [];

        foreach ($passengers as $passenger) {
            if ($passenger->place_from) {
                $engagedSeatsFrom[] = [
                    'passenger_id' => $passenger->id,
                    'place' => $passenger->place_from,
                ];
            }

            if ($passenger->place_back) {
                $engagedSeatsBack[] = [
                    'passenger_id' => $passenger->id,
                    'place' => $passenger->place_back
                ];
            }
        }


        $this->response = [
            'data' => [
                'occupied_from' => $engagedSeatsFrom,
                'occupied_back' => $engagedSeatsBack,
            ]
        ];



        return response()->json($this->response, $this->status);
    }
}
