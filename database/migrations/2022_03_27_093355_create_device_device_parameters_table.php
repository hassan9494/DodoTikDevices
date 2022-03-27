<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceDeviceParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_device_parameters', function (Blueprint $table) {
            $table->foreignId('device_parameters_id')->constrained('device_parameters');
            $table->foreignId('device_id')->constrained('devices');
            $table->primary(['device_parameters_id','device_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_device_parameters');
    }
}
