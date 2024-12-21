<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GpsCoordinate;
use Illuminate\Http\Request;


class GpsCoordinateController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'date_time' => 'required',
        ]);

        $gpsCoordinate = GpsCoordinate::create([
            'user_id' => $validated['user_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'date_time' => $validated['date_time'],
        ]);

        return response()->json(['message' => 'Data stored successfully']);
    }
}

