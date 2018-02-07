<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->json('name');
            $table->integer('type')->unsigned();
            $table->boolean('is_unlocked');
            $table->integer('speed')->unsigned();
            $table->integer('attack')->unsigned();
            $table->integer('defense')->unsigned();
            $table->integer('supply')->unsigned();
            $table->integer('train_cost')->unsigned();
            $table->integer('train_time')->unsigned();
            $table->json('description');
            $table->integer('detection')->unsigned()->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->integer('research_experience')->unsigned()->nullable();
            $table->integer('research_cost')->unsigned()->nullable();
            $table->integer('research_time')->unsigned()->nullable();
            $table->integer('sort_order')->unsigned()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
