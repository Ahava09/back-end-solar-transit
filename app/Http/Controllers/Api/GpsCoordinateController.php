<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NodeMicroservice;
use App\Models\GpsCoordinate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
        // \Log::info('id: ', $id);
        // ob_start();

        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
        $request = $this->microservice->getCoordinatesPerson($id);
        // \Log::info('Microservice data: ', $request);

        $request["latitude"] = $request["currentPosition"]["latitude"]; 
        $request["longitude"] = $request["currentPosition"]["longitude"]; 

        // error_log(ob_clean(), 4);
        $req = new Request($request);
        // var_dump($req);
        return $this->store($req);
    }


    public function store(Request $request)
    {
        // var_dump($request);
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
            'latitude' => 'required', 
            'longitude' => 'required',
            'last_time_seen' => 'required',
        ]);

        // $latitude = $request->input('currentPosition.latitude');
        // $longitude = $request->input('currentPosition.longitude');

        $gpsCoordinate = GpsCoordinate::create([
            'user_id' => $validated['id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'date_time' => $validated['last_time_seen'],
        ]);
        return response()->json(['message' => 'Data stored successfully']);
    }
}

