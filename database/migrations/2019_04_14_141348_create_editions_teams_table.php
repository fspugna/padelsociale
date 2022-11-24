<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionsTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editions_teams', function (Blueprint $table) {

            $table->unsignedInteger('id_edition');
            $table->unsignedBigInteger('id_team');

            $table->primary(['id_edition', 'id_team']);

            $table->timestamps();

            $table->foreign('id_edition')
                ->references('id')->on('editions')
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
        Schema::dropIfExists('editions_teams');
    }
}
