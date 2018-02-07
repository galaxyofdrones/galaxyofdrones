<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');
            NestedSet::columns($table);
            $table->json('name');
            $table->integer('type')->unsigned();
            $table->integer('end_level')->unsigned();
            $table->integer('construction_experience')->unsigned();
            $table->integer('construction_cost')->unsigned();
            $table->integer('construction_time')->unsigned();
            $table->json('description');
            $table->integer('limit')->unsigned()->nullable();
            $table->integer('defense')->unsigned()->nullable();
            $table->integer('detection')->unsigned()->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->integer('supply')->unsigned()->nullable();
            $table->integer('mining_rate')->unsigned()->nullable();
            $table->integer('production_rate')->unsigned()->nullable();
            $table->double('defense_bonus')->unsigned()->nullable();
            $table->double('construction_time_bonus')->unsigned()->nullable();
            $table->double('trade_time_bonus')->unsigned()->nullable();
            $table->double('train_time_bonus')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
