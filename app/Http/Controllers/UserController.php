<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GpsCoordinate;
use App\Services\NodeMicroservice;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $microservice;
    protected $geocodingService;

    public function __construct(NodeMicroservice $microservice, GeocodingService $geocodingService)
    {
        $this->microservice = $microservice;
        $this->geocodingService = $geocodingService;
    }

    public function index(Request $request, $id)
    {
        $coordinate = User::with(['coordinates' => function ($query) {
            $query->orderBy('date_time', 'asc'); 
        }])->find($id);

        if (!$coordinate) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($coordinate);
    }

    public function getAll(Request $request)
    {
        $jwt = str_replace('Bearer ', '', $request->header('Authorization'));

        try {
            $user = User::getUserDataFromToken($jwt);
            if (!$user) {
                return response()->json(['message' => 'No User'], 404);
            } else {
                $userRole = $user['role'];
                if ($userRole == 'admin') {
                    return response()->json(User::All());
                } else {
                    return response()->json(User::where('id', $user['user_id'])->get());
                }
            }
        } catch (\RuntimeException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        
    }
    
    public function tracking(Request $request)
    {
        $users = User::All();
        $coordinates = [];
        foreach ($users as $user) {
            $coordinate = User::with(['coordinates' => function ($query) {
                $query->orderBy('date_time', 'asc'); 
            }])->find($user->id);
            $coordinates[] = $coordinate;
        }

        return response()->json($coordinates);
    }

    public function syncPersons(Request $request)
    {
        $persons = $this->microservice->getAllPersons();

        foreach ($persons as $person) {
            User::updateOrCreate(
                ['email' => $person['email'], 'id' => $person['id']],
                [
                    'name' => $person['name'],
                    'password' => $person['password'],
                    'role' => $person['role'],
                ]
            );
            // $coordinates = $geocodingService->getCoordinatesFromAddress($person['address']);
            GpsCoordinate::updateOrCreate(
                [
                    'user_id' => $person['id'], 
                    'latitude' => $person["currentPosition"]['latitude'],
                    'longitude' => $person["currentPosition"]['longitude'],
                    // 'latitude' => $coordinates['latitude'],
                    // 'longitude' => $coordinates['longitude'],
                    'date_time' => $person['last_time_seen']
                ]
            );
        }

        return response()->json(['message' => 'Users synchronized successfully.']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createTokenWithClaims();

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
        ],201);
    }

}
