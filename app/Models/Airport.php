<?php

namespace App\Models;

use App\Models\Airport as AirportModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'airports';
}
