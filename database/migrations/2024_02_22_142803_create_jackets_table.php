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
        Schema::create('jackets', function (Blueprint $table) {
            $table->id();
            $table->string('modelno');
            $table->integer('batteryLevel');
            $table->dateTime('start_rent_time');
            $table->dateTime('end_rent_time');
            $table->boolean('active')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('area_id')->constrained('areas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jackets');
    }
};
