<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('grid_id')->unsigned()->unique();
            $table->foreign('grid_id')
                ->references('id')
                ->on('grids')
                ->onDelete('cascade');

            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
            $table->timestamp('ended_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('trainings');
    }
}
