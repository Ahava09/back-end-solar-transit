<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NodeMicroservice;
use App\Models\GpsCoordinate;
use App\Models\User;
use Illuminate\Http\Request;


class GpsCoordinateController extends Controller
{ 
    public function __construct(NodeMicroservice $microservice)
    {
        $this->microservice = $microservice;
    }

    public function index($id)
    {
        $coordinates = User::with('coordinates')->find($id);

        if (!$coordinates) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($coordinates);
    }


    public function syncGpsPerson($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
        $request = $this->microservice->getCoordinatesPerson($id);
        \Log::info('Microservice data:', $request->all());
    
        return $this->store(new Request($request));
    }


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

