<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NodeMicroservice;
use Illuminate\Console\Command;

class SyncPersons extends Command
{
    protected $signature = 'sync:persons';
    protected $description = 'Synchronize persons from Node.js microservice';
    protected $microservice;

    public function __construct(NodeMicroservice $microservice)
    {
        parent::__construct();
        $this->microservice = $microservice;
    }

    public function handle()
    {
        $persons = $this->microservice->getAllPersons();

        foreach ($persons as $person) {
            User::updateOrCreate(
                ['email' => $person['email']], // Clé unique pour identifier l'utilisateur
                [
                    'name' => $person['name'],
                    'password' => bcrypt('default_password'), // Générer un mot de passe par défaut si nécessaire
                ]
            );
        }

        $this->info('Users synchronized successfully.');
    }
}
