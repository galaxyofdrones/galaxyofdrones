<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->json('name');
            $table->boolean('is_unlocked');
            $table->double('frequency')->unsigned();
            $table->double('efficiency')->unsigned();
            $table->json('description');
            $table->integer('research_experience')->unsigned()->nullable();
            $table->integer('research_cost')->unsigned()->nullable();
            $table->integer('research_time')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
