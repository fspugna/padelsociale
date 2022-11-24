<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_players', function (Blueprint $table) {            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_team');
            $table->unsignedBigInteger('id_player');
            $table->boolean('starter');
            $table->timestamps();

            $table->unique('id_team', 'id_player');

            $table->foreign('id_team')
                    ->references('id')->on('teams')
                    ->onDelete('cascade');

            $table->foreign('id_player')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_players');
    }
}
