<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacroMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('macro_matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('matchcode');
            $table->unsignedBigInteger('id_team1')->nullable();
            $table->unsignedBigInteger('id_team2')->nullable();
            $table->unsignedInteger('id_club')->nullable();
            $table->date("date");
            $table->time('time');
            $table->unsignedBigInteger('prev_matchcode')->nullable();
            $table->unsignedBigInteger('next_matchcode')->nullable();
            $table->tinyInteger('id_user')->default(0);
            $table->tinyInteger('a_tavolino')->default(0);
            $table->string('note')->nullable();
            $table->unsignedBigInteger('jolly_team1')->nullable();
            $table->unsignedBigInteger('jolly_team2')->nullable();
            $table->timestamps();
            
            $table->foreign('id_team1')
                ->references('id')->on('macro_teams')
                ->onDelete('cascade');

            $table->foreign('id_team2')
                ->references('id')->on('macro_teams')
                ->onDelete('cascade');

            $table->foreign('id_club')
                ->references('id')->on('clubs')
                ->onDelete('cascade');

            $table->foreign('matchcode')
                ->references('id')->on('matchcodes')
                ->onDelete('cascade');

            $table->foreign('prev_matchcode')
                ->references('id')->on('matchcodes')
                ->onDelete('cascade');

            $table->foreign('next_matchcode')
                ->references('id')->on('matchcodes')
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
        Schema::dropIfExists('macro_matches');
    }
}
