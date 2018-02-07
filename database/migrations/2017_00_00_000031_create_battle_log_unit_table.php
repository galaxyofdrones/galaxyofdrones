<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogUnitTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('battle_log_unit', function (Blueprint $table) {
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

            $table->integer('owner')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->integer('losses')->unsigned();

            $table->primary(['battle_log_id', 'unit_id', 'owner']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('battle_log_unit');
    }
}
