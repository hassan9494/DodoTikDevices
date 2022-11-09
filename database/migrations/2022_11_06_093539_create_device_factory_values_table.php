<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceFactoryValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_factory_values', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id')->unsigned();
            $table->integer('factory_id')->unsigned();
            $table->json('parameters');
            $table->dateTime('time_of_read');
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
        Schema::dropIfExists('device_factory_values');
    }
}
