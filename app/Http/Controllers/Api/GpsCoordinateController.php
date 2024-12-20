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
        ]);

        $gpsCoordinate = GpsCoordinate::create([
            'user_id' => $validated['user_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'date_time' => $validated['timestamp'],
        ]);

        return response()->json(['message' => 'Data stored successfully']);
    }
}

