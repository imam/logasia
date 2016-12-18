<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwapBodyTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swap_body_trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('price');
            $table->integer('date_id')->unique()->unsigned()->nullable();
            $table->integer('vehicles_available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('swap_body_trucks');
    }
}
