<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('year');
            $table->date('date');
            $table->unsignedBigInteger('id_player');
            $table->unsignedInteger('points');
            $table->unsignedInteger('id_city');
            $table->unsignedInteger('id_match');
            $table->tinyInteger('match_won');
            $table->tinyInteger('match_lost');
            $table->tinyInteger('match_deuce');
            $table->tinyInteger('set_won');
            $table->tinyInteger('set_lost');
            $table->tinyInteger('games_won');
            $table->tinyInteger('games_lost');
            $table->timestamps();

            $table->unique(['year', 'date', 'id_player']);

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
        Schema::dropIfExists('rankings');
    }
}
