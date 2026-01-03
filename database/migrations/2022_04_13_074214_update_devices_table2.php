<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDevicesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            if (!Schema::hasColumn('devices', 'longitude')) {
                $table->string('longitude')->nullable();
            }

            if (!Schema::hasColumn('devices', 'latitude')) {
                $table->string('latitude')->nullable();
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
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'longitude')) {
                $table->dropColumn('longitude');
            }

            if (Schema::hasColumn('devices', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
}
