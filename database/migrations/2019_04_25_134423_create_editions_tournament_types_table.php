<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionsTournamentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editions_tournament_types', function (Blueprint $table) {
            $table->unsignedInteger('id_edition');
            $table->unsignedInteger('id_tournament_type');
            $table->primary(['id_edition', 'id_tournament_type']);
            $table->timestamps();

            $table->foreign('id_edition')
                ->references('id')->on('editions')
                ->onDelete('cascade');

            $table->foreign('id_tournament_type')
                ->references('id')->on('tournament_types')
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
        Schema::dropIfExists('editions_tournament_types');
    }
}
