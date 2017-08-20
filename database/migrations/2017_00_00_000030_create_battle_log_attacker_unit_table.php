<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBattleLogAttackerUnitTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('battle_log_attacker_unit', function (Blueprint $table) {
            $table->bigInteger('battle_log_id')->unsigned();
            $table->foreign('battle_log_id')
                ->references('id')
                ->on('battle_logs')
                ->onDelete('cascade');

            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
            $table->integer('losses')->unsigned();

            $table->primary(['battle_log_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('battle_log_attacker_unit');
    }
}
