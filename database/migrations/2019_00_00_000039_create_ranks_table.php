<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->unique();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('mission_count')->unsigned();
            $table->integer('expedition_count')->unsigned();
            $table->integer('planet_count')->unsigned();
            $table->integer('winning_battle_count')->unsigned();
            $table->integer('losing_battle_count')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ranks');
    }
}
