<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanetsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('planets', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->string('name');
            $table->string('custom_name')->nullable();
            $table->integer('x')->unsigned();
            $table->integer('y')->unsigned();
            $table->integer('size')->unsigned();
            $table->integer('capacity')->unsigned()->nullable();
            $table->integer('supply')->unsigned()->nullable();
            $table->integer('mining_rate')->unsigned()->nullable();
            $table->integer('production_rate')->unsigned()->nullable();
            $table->double('defense_bonus')->unsigned()->nullable();
            $table->double('construction_time_bonus')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['x', 'y']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('planets');
    }
}
