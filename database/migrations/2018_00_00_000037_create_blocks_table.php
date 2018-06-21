<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('blocked_id')->unsigned();
            $table->foreign('blocked_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['blocked_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
}
