<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShieldsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('shields', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('planet_id')->unsigned()->unique();
            $table->foreign('planet_id')
                ->references('id')
                ->on('planets')
                ->onDelete('cascade');

            $table->timestamp('ended_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('shields');
    }
}
