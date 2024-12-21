<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_maps.api_key');
    }

    public function getCoordinatesFromAddress($address)
    {
        try {
            $url = "https://maps.googleapis.com/maps/api/geocode/json";
            try {
                $response = Http::withOptions([
                    'verify' => false,
                ])->get($url, [
                    'address' => $address,
                    'key' => $this->apiKey,
                ]);
        
                // if ($response->successful()) {
                //     dd($response->json());
                // } else {
                //     dd([
                //         'status' => $response->status(),
                //         'error' => $response->body(),
                //     ]);
                // }

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['results'])) {
                        $location = $data['results'][0]['geometry']['location'];
                        var_dump($location);
                        return [
                            'latitude' => $location['lat'],
                            'longitude' => $location['lng'],
                        ];
                    }
                    return ['error' => 'No results found for the given address.'];
                }
    
                return ['error' => 'Failed to fetch data from the Geocoding API.'];
            } catch (\Exception $e) {
                dd(['error' => $e->getMessage()]);
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
