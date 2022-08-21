<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentComponetSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_component_settings', function (Blueprint $table) {
            $table->foreignId('component_id')->constrained('components');
            $table->foreignId('component_settings_id')->constrained('component_settings');
            $table->primary(['component_id','component_settings_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('component_componet_settings');
    }
}
