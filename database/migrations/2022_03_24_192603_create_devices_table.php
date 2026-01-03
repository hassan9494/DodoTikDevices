<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('device_id')->unique();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('type_id');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->integer('time_between_two_read')->default(0);
            $table->integer('tolerance')->default(2);
            $table->softDeletes();
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
        Schema::dropIfExists('devices');
    }
}
