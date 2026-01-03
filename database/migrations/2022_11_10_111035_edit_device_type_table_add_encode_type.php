<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDeviceTypeTableAddEncodeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_types', function (Blueprint $table) {
            if (!Schema::hasColumn('device_types', 'encode_type')) {
                $table->integer('encode_type')->default(1);
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
        Schema::table('device_types', function (Blueprint $table) {
            if (Schema::hasColumn('device_types', 'encode_type')) {
                $table->dropColumn('encode_type');
            }
        });
    }
}
