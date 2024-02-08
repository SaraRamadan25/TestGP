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
        Schema::create('health', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('age');
            $table->double('height');
            $table->double('weight');
            $table->double('heart_rate');
            $table->string('blood_type');
            $table->text('diseases');
            $table->text('allergies');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health');
    }
};
