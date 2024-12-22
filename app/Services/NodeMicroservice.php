<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NodeMicroservice
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.microservice_node.url');
    }

    protected function request($method, $endpoint, $data = [])
    {
        $response = Http::{$method}("{$this->baseUrl}/{$endpoint}", $data);
        if ($response->successful()) {
            return $response->json();
        }

        throw new \RuntimeException("Request to {$endpoint} failed", $response->status());
    }

    public function getAllPersons()
    {
        
        return $this->request('get', 'api/persons');
    }

    public function getCoordinatesPerson($id)
    {
        return $this->request('get', "coordinates/{$id}");
    }

    public function getPersonById($id)
    {
        return $this->request('get', "api/persons/{$id}");
    }
}
