<?php

namespace App\Models;

use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flights extends Model
{
    use HasFactory;

    public function to()
    {
        return Airport::where('id', $this->from_id)->first();
    }

    public function back()
    {
        return Airport::where('id', $this->to_id)->first();
    }
    protected $table = 'flights';
}
