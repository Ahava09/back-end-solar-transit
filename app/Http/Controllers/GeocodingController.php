<?php

namespace App\Http\Controllers;

use App\Services\GeocodingService;
use Illuminate\Http\Request;

class GeocodingController extends Controller
{
    protected $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function getCoordinates(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);
        var_dump($request->input('address'));

        $coordinates = $this->geocodingService->getCoordinatesFromAddress($request->input('address'));

        return response()->json($coordinates);
    }
}
