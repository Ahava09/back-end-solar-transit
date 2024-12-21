<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function syncPersons()
    {
        $persons = $this->microservice->getAllPersons();

        foreach ($persons as $person) {
            User::updateOrCreate(
                ['email' => $person['email'], 'id' => $person['id']],
                [
                    'name' => $person['name'],
                    'password' => bcrypt('default_password'),
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

        // Générer un jeton d'authentification (JWT ou Sanctum)
        $token = $user->createTokenWithClaims();

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
        ],201);
    }
    
}
