<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionUnitTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('expedition_unit', function (Blueprint $table) {
            $table->bigInteger('expedition_id')->unsigned();
            $table->foreign('expedition_id')
                ->references('id')
                ->on('expeditions')
                ->onDelete('cascade');

            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();

            $table->primary(['expedition_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('expedition_unit');
    }
}
