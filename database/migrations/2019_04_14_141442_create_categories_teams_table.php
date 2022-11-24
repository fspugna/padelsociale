<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id_category');
            $table->unsignedBigInteger('id_team');
            $table->timestamps();

            $table->foreign('id_category')
                ->references('id')->on('categories')
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
        Schema::dropIfExists('categories_teams');
    }
}
