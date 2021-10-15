<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Passenger;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    private $flightCost = 0;


    public function toArray($request)
    {
        $flights[] = $this->fillFlight($this->flightTo());
        $flights[] = $this->fillFlight($this->flightBack());
        $passengers = Passenger::where('booking_id', $this->id)->get();
        $this->flightCost *= count($passengers);
        return [
            'data' => [
                'code' => $this->code,
                'cost' => $this->flightCost,
                'flights' => $flights,
                'passengers' => $passengers
            ]
        ];
    }

    private function fillFlight($flight)
    {
        $flightTo = $flight->to();
        $flightFrom = $flight->from();
        $this->flightCost += $flight->cost;
        return [
            'flight_id' => $flight->id,
            'flight_code' => $flight->code,
            'from' => $this->fillAirport($flight, $flightTo, $flight->time_from, $this->date_from),
            'to' => $this->fillAirport($flight, $flightFrom, $flight->time_to, $this->date_back),
            'cost' => $flight->cost,
            'availability' => 'neznau',
        ];
    }

    private function fillAirport($flight, $airport, $time, $date){

        return [
            'city' => $airport->city,
            'airport' => $airport->name,
            'iata' => $airport->iata,
            'date' => $date,
            'time' => $time,
        ];
    }
}
