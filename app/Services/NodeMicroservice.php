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

    public function getAllPersons()
    {
        $response = Http::get("{$this->baseUrl}/api/persons");

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception('Failed to fetch persons from Node.js microservice.');
    }

    public function getPersonById($id)
    {
        $response = Http::get("{$this->baseUrl}/api/persons/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Failed to fetch person with ID {$id} from Node.js microservice.");
    }
}
