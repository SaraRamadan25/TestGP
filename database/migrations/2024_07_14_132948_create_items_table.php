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
        Schema::create('items', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('image');
            $table->boolean('popular');
            $table->integer('price');
            $table->string('review');
            $table->integer('quantity');
            $table->boolean('favorite');
            $table->text('description');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('order_id')->constrained('orders');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
