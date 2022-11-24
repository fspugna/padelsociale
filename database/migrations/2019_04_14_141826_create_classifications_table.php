<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {   
            $table->bigIncrements('id');         
            $table->unsignedBigInteger('id_group');
            $table->unsignedBigInteger('id_team');
            $table->unsignedBigInteger('id_match');
            $table->tinyInteger('points')->default(0)->comment('punti');
            $table->tinyInteger('played')->default(0)->comment('partite giocate');
            $table->tinyInteger('won')->default(0)->comment('partite vinte');
            $table->tinyInteger('lost')->default(0)->comment('partite perse');
            $table->tinyInteger('draws')->default(0)->comment('partite terminate in pareggio');
            $table->tinyInteger('set_won')->default(0)->comment('set vinti');
            $table->tinyInteger('set_lost')->default(0)->comment('set persi');
            $table->tinyInteger('games_won')->default(0)->comment('giochi vinti');
            $table->tinyInteger('games_lost')->default(0)->comment('giochi persi');

            $table->unique(['id_group', 'id_team', 'id_match']);
            $table->timestamps();

            $table->foreign('id_group')
                ->references('id')->on('groups')
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
        Schema::dropIfExists('classifica');
    }
}
