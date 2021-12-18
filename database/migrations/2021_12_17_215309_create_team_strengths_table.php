<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamStrengthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_strengths', function(Blueprint $table)
        {
            $table->id();
            $table->integer('team_id')->nullable();
            $table->boolean('is_home')->nullable()->default(0);
            $table->enum('strength', array('weak','average','strong'))->nullable();
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
        Schema::dropIfExists('team_strengths');
    }
}
