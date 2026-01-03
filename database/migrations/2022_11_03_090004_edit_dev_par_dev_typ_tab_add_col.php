<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDevParDevTypTabAddCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_parameters_device_type', function (Blueprint $table) {
            if (!Schema::hasColumn('device_parameters_device_type', 'color')) {
                $table->string('color')->default('#000000');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_parameters_device_type', function (Blueprint $table) {
            if (Schema::hasColumn('device_parameters_device_type', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
}
