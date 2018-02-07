<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogResourceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('battle_log_resource', function (Blueprint $table) {
            $table->bigInteger('battle_log_id')->unsigned();
            $table->foreign('battle_log_id')
                ->references('id')
                ->on('battle_logs')
                ->onDelete('cascade');

            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
            $table->integer('losses')->unsigned();

            $table->primary(['battle_log_id', 'resource_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('battle_log_resource');
    }
}
