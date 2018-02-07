<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('battle_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('start_id')->unsigned();
            $table->foreign('start_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->integer('end_id')->unsigned();
            $table->foreign('end_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->integer('attacker_id')->unsigned();
            $table->foreign('attacker_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('defender_id')->unsigned()->nullable();
            $table->foreign('defender_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->string('start_name');
            $table->string('end_name');
            $table->integer('type')->unsigned();
            $table->integer('winner')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('battle_logs');
    }
}
