<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league', function(Blueprint $table)
        {
            $table->id();
            $table->integer('team_id')->nullable();
            $table->integer('points')->nullable();
            $table->integer('played')->nullable();
            $table->integer('won')->nullable();
            $table->integer('lose')->nullable();
            $table->integer('draw')->nullable();
            $table->integer('goal_drawn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league');
    }
}
