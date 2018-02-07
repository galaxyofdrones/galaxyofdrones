<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionResourceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mission_resource', function (Blueprint $table) {
            $table->bigInteger('mission_id')->unsigned();
            $table->foreign('mission_id')
                ->references('id')
                ->on('missions')
                ->onDelete('cascade');

            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();

            $table->primary(['mission_id', 'resource_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('mission_resource');
    }
}
