<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGridsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('grids', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('planet_id')->unsigned();
            $table->foreign('planet_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->integer('building_id')->unsigned()->nullable();
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('set null');

            $table->integer('x');
            $table->integer('y');
            $table->integer('level')->unsigned()->nullable();
            $table->integer('type')->unsigned();
            $table->timestamps();

            $table->unique(['planet_id', 'x', 'y']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('grids');
    }
}
