<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionLogResourceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mission_log_resource', function (Blueprint $table) {
            $table->bigInteger('mission_log_id')->unsigned();
            $table->foreign('mission_log_id')
                ->references('id')
                ->on('mission_logs')
                ->onDelete('cascade');

            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();

            $table->primary(['mission_log_id', 'resource_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('mission_log_resource');
    }
}
