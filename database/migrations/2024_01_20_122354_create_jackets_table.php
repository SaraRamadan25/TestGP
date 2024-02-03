<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jackets', function (Blueprint $table) {
            $table->id();
            $table->string('modelno');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('batteryLevel');
            $table->dateTime('start_rent_time');
            $table->dateTime('end_rent_time');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jackets');
    }
}
