<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('capital_id')->unsigned()->nullable();
            $table->integer('current_id')->unsigned()->nullable();
            $table->string('username', 20)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_enabled');
            $table->bigInteger('energy')->unsigned();
            $table->bigInteger('solarion')->unsigned();
            $table->bigInteger('experience')->unsigned();
            $table->bigInteger('production_rate')->unsigned();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_capital_changed')->nullable();
            $table->timestamp('last_energy_changed')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
