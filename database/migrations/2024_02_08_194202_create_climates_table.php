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
        Schema::create('climates', function (Blueprint $table) {
            $table->id();
            $table->double('tide');
            $table->double('sea_level');
            $table->double('wind');
            $table->double('temperature');
            $table->string('day_name');
            $table->date('day_date');
            $table->foreignId('area_id')->constrained('areas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climates');
    }
};
