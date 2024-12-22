<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }

};


// <?php
// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// class ChangeUserIdColumnType extends Migration
// {
//     /**
//      * Exécutez la migration.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         // Modifiez le type de la colonne 'id' de BIGINT à INTEGER
//         Schema::table('users', function (Blueprint $table) {
//             // Remarque : Si la colonne 'id' est déjà présente, il faut d'abord la supprimer avant de la recréer.
//             // Cette opération peut entraîner la perte de données si la table contient des enregistrements.
//             $table->dropColumn('id');
//             $table->id('id')->change(); // Redéfinir l'ID comme integer
//         });
//     }

//     /**
//      * Annulez la migration.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         // Remettez le type d'ID à BIGINT si nécessaire
//         Schema::table('users', function (Blueprint $table) {
//             $table->dropColumn('id');
//             $table->bigIncrements('id'); // Revenir à BIGINT
//         });
//     }
// }
