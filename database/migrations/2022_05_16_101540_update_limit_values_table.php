<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLimitValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('limit_values', function (Blueprint $table) {
            if (!Schema::hasColumn('limit_values', 'min_warning')) {
                $table->boolean('min_warning')->default(false);
            }

            if (!Schema::hasColumn('limit_values', 'max_warning')) {
                $table->boolean('max_warning')->default(false);
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
        Schema::table('limit_values', function (Blueprint $table) {
            if (Schema::hasColumn('limit_values', 'min_warning')) {
                $table->dropColumn('min_warning');
            }

            if (Schema::hasColumn('limit_values', 'max_warning')) {
                $table->dropColumn('max_warning');
            }
        });
    }
}
