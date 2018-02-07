<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopulationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('populations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('planet_id')->unsigned();
            $table->foreign('planet_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
            $table->timestamps();

            $table->unique(['planet_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('populations');
    }
}
