<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementResourceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('movement_resource', function (Blueprint $table) {
            $table->bigInteger('movement_id')->unsigned();
            $table->foreign('movement_id')
                ->references('id')
                ->on('movements')
                ->onDelete('cascade');

            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();

            $table->primary(['movement_id', 'resource_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('movement_resource');
    }
}
