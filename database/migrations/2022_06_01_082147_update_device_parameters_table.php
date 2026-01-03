<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDeviceParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_parameters', function (Blueprint $table) {
            if (!Schema::hasColumn('device_parameters', 'unit')) {
                $table->string('unit')->nullable();
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
        Schema::table('device_parameters', function (Blueprint $table) {
            if (Schema::hasColumn('device_parameters', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }
}
