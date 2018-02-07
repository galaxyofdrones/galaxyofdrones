<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementUnitTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('movement_unit', function (Blueprint $table) {
            $table->bigInteger('movement_id')->unsigned();
            $table->foreign('movement_id')
                ->references('id')
                ->on('movements')
                ->onDelete('cascade');

            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();

            $table->primary(['movement_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('movement_unit');
    }
}
