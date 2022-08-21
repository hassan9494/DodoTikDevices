<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices_components', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id')->unsigned();
            $table->integer('component_id')->unsigned();
            $table->integer('order')->default(1);
            $table->integer('width')->default(6);
            $table->json('settings');
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
        Schema::dropIfExists('devices_components');
    }
}
