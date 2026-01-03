<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            if (!Schema::hasColumn('components', 'slug')) {
                $table->string('slug')->after('name');
            }

            if (!Schema::hasColumn('components', 'settings')) {
                if (DB::getDriverName() === 'sqlite') {
                    $table->longText('settings')->nullable();
                } else {
                    $table->json('settings')->nullable();
                }
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
        Schema::table('components', function (Blueprint $table) {
            if (Schema::hasColumn('components', 'slug')) {
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('components', 'settings')) {
                $table->dropColumn('settings');
            }
        });
    }
}
