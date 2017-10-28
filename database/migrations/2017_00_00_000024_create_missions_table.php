<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('planet_id')->unsigned();
            $table->foreign('planet_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->integer('energy')->unsigned();
            $table->integer('experience')->unsigned();
            $table->timestamp('ended_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('missions');
    }
}
