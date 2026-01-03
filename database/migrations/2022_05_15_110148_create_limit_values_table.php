<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLimitValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limit_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('device_id');
            $table->longText('min_value');
            $table->longText('max_value');
            $table->boolean('min_warning')->default(false);
            $table->boolean('max_warning')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limit_values');
    }
}
