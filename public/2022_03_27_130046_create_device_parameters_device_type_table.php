<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceParametersDeviceTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_parameters_device_type', function (Blueprint $table) {
            $table->foreignId('device_parameters_id')->constrained('device_parameters');
            $table->foreignId('device_type_id')->constrained('device_types');
            $table->primary(['device_parameters_id','device_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_parameters_device_type');
    }
}
