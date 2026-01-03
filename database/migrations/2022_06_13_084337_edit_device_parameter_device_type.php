<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDeviceParameterDeviceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_parameters_device_type')) {
            return;
        }

        if (Schema::hasColumn('device_parameters_device_type', 'order')) {
            return;
        }

        Schema::table('device_parameters_device_type', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('device_parameters_device_type')) {
            return;
        }

        if (!Schema::hasColumn('device_parameters_device_type', 'order')) {
            return;
        }

        Schema::table('device_parameters_device_type', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
