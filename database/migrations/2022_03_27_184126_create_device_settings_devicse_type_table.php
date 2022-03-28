<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceSettingsDevicseTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_settings_device_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_settings_id')->constrained('device_settings');
            $table->foreignId('device_type_id')->constrained('device_types');
            $table->string('value')->default(0);$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_settings_device_type');
    }
}
