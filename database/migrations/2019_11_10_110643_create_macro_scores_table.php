<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacroScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('macro_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('id_match');
            $table->unsignedBigInteger('id_team');
            $table->char('set', 1)->comment('1,2,3,4,5 oppure F per Risultato Finale');
            $table->string('side');
            $table->tinyInteger('points');
            $table->timestamps();

            $table->primary(['id_match', 'id_team']);

            $table->foreign('id_match')
                ->references('id')->on('macro_matches')
                ->onDelete('cascade');

            $table->foreign('id_team')
                ->references('id')->on('macro_teams')
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
        Schema::dropIfExists('macro_scores');
    }
}
