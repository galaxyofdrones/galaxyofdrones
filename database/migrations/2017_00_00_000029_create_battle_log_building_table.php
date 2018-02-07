<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogBuildingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('battle_log_building', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('battle_log_id')->unsigned();
            $table->foreign('battle_log_id')
                ->references('id')
                ->on('battle_logs')
                ->onDelete('cascade');

            $table->integer('building_id')->unsigned();
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');

            $table->integer('level')->unsigned();
            $table->integer('losses')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('battle_log_building');
    }
}
