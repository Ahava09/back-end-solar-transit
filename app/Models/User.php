<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Crée une colonne auto-incrémentée `id`
            $table->string('name'); // Colonne pour le nom
            $table->string('email')->unique(); // Colonne pour l'email unique
            $table->string('password'); // Colonne pour le mot de passe
            $table->string('role')->nullable(); // Colonne pour le rôle
            $table->timestamps(); // Colonne pour les timestamps `created_at` et `updated_at`
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
