<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('upgrades', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('grid_id')->unsigned()->unique();
            $table->foreign('grid_id')
                ->references('id')
                ->on('grids')
                ->onDelete('cascade');

            $table->integer('level');
            $table->timestamp('ended_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('upgrades');
    }
}
