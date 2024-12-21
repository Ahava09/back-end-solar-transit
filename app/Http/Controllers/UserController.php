<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GpsCoordinate;
use App\Services\NodeMicroservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $microservice;

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

    public function getAll()
    {
        $users = User::All();

        if (!$users) {
            return response()->json(['message' => 'No User'], 404);
        }

        return response()->json($users);
    }
    
    public function tracking()
    {
        $users = User::All();
        $coordinates = [];
        foreach ($users as $user) {
            $coordinate = User::with('coordinates')->find($user->id);
            $coordinates[] = $coordinate;
        }

        return response()->json($coordinates);
    }

    public function syncPersons()
    {
        $persons = $this->microservice->getAllPersons();

        foreach ($persons as $person) {
            User::updateOrCreate(
                ['email' => $person['email'], 'id' => $person['id']],
                [
                    'name' => $person['name'],
                    'password' => $person['password'],
                ]
            );
            GpsCoordinate::updateOrCreate(
                [
                    'user_id' => $person['id'], 
                    'latitude' => $person["currentPosition"]['latitude'],
                    'longitude' => $person["currentPosition"]['longitude'],
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
