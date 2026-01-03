<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('devices', 'type_id')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->unsignedInteger('type_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('devices', 'type_id')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->dropColumn('type_id');
            });
        }
    }
}
