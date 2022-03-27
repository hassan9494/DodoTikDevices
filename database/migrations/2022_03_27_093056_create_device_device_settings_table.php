<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceDeviceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_device_settings', function (Blueprint $table) {
            $table->foreignId('device_settings_id')->constrained('device_settings');
            $table->foreignId('device_id')->constrained('devices');
            $table->primary(['device_settings_id','device_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_device_settings');
    }
}
