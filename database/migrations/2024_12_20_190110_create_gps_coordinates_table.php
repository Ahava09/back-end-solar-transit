<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpsCoordinatesTable extends Migration
{
    public function up()
    {
        Schema::create('gps_coordinates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('date_time')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gps_coordinates');
    }
}
