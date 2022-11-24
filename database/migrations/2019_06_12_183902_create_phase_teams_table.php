<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhaseTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phase_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id_phase');
            $table->unsignedBigInteger('id_team');
            $table->timestamps();

            $table->foreign('id_phase')
                ->references('id')->on('phases')
                ->onDelete('cascade');

            $table->foreign('id_team')
                ->references('id')->on('teams')
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
        Schema::dropIfExists('bracket_teams');
    }
}
