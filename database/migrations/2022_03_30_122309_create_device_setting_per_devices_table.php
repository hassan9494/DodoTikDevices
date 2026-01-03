<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDeviceSettingPerDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_setting_per_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('device_id');

            if (DB::getDriverName() === 'sqlite') {
                $table->longText('settings');
            } else {
                $table->longText('settings')->charset('utf8mb4')->collation('utf8mb4_bin');
            }

            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement(
                'ALTER TABLE device_setting_per_devices ADD CONSTRAINT device_setting_per_devices_settings_json CHECK (json_valid(settings))'
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_setting_per_devices');
    }
}
