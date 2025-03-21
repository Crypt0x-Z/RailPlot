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
        Schema::create('line_stations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("lineId")->references("id")->on("routes")->onDelete("cascade");
            $table->foreignId("stationId")->references("id")->on("stations")->onDelete("cascade");
            $table->boolean("isStart");
            $table->boolean("isEnd");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_stations');
    }
};
