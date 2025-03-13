<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('routes', function (Blueprint $table) {
            $table->id(); // This creates an unsignedBigInteger by default
            $table->string('name');
            $table->foreignId('start_station_id')->constrained('stations')->onDelete('cascade');
            $table->foreignId('end_station_id')->constrained('stations')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('routes');
    }
};