<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('planet_id')->unsigned();
            $table->foreign('planet_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
            $table->timestamp('last_quantity_changed')->nullable();
            $table->timestamps();

            $table->unique(['planet_id', 'resource_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
