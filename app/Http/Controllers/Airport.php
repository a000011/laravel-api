<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport as AirportModel;

class Airport extends Controller
{
    public function getAirport(Request $request)
    {
        $query = $request->input('query');
        $airports = AirportModel::all();
        $findedAirport = null;

        foreach ($airports as $airport) {
            if( strlen($query) < 4) {
                similar_text(strtoupper($airport->iata), strtoupper($query), $percent);
                if($percent > 50){
                    $findedAirport = $airport;
                }
            }
            else{
                similar_text(strtoupper($airport->city), strtoupper($query), $percent);
                if($percent > 50){
                    $findedAirport = $airport;
                }
            }
        }

        return response()->json($findedAirport);
    }
}
