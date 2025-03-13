<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('trains', function (Blueprint $table) {
            $table->id(); // This is an unsignedBigInteger by default
            $table->string('name');
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade'); // Ensure it references the correct table
            $table->integer('capacity');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('trains');
    }
};