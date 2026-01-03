<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDeviceTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_types', function (Blueprint $table) {
            if (!Schema::hasColumn('device_types', 'is_gateway')) {
                $table->boolean('is_gateway')->default(false);
            }

            if (!Schema::hasColumn('device_types', 'is_need_response')) {
                $table->boolean('is_need_response')->default(false);
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
            if (Schema::hasColumn('device_types', 'is_gateway')) {
                $table->dropColumn('is_gateway');
            }

            if (Schema::hasColumn('device_types', 'is_need_response')) {
                $table->dropColumn('is_need_response');
            }
        });
    }
}
