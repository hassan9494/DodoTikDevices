<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_factories', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id')->unsigned();
            $table->integer('factory_id')->unsigned();
            $table->dateTime('start_date');
            $table->boolean('is_attached')->default(0);
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
        Schema::dropIfExists('device_factories');
    }
}
