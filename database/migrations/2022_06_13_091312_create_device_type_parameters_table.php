<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTypeParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_parameters_device_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_parameters_id')->constrained('device_parameters');
            $table->foreignId('device_type_id')->constrained('device_types');
            $table->integer('order')->nullable();
            $table->integer('order')->default(4);
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
        Schema::dropIfExists('device_type_parameters');
    }
}
