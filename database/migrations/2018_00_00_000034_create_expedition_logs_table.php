<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('expedition_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('star_id')->unsigned();
            $table->foreign('star_id')
                ->references('id')
                ->on('stars')
                ->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('solarion')->unsigned();
            $table->integer('experience')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('expedition_logs');
    }
}
