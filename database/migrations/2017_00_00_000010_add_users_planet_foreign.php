<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersPlanetForeign extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('capital_id')
                ->references('id')
                ->on('planets')
                ->onDelete('set null');

            $table->foreign('current_id')
                ->references('id')
                ->on('planets')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['capital_id']);
            $table->dropForeign(['current_id']);
        });
    }
}
