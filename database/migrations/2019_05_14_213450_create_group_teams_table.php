<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id_group');
            $table->unsignedBigInteger('id_team');
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
        Schema::dropIfExists('group_teams');
    }
}
