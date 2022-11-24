<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editions_categories', function (Blueprint $table) {
            $table->unsignedInteger('id_edition');
            $table->unsignedBigInteger('id_category');
            $table->primary(['id_edition', 'id_category']);
            $table->timestamps();

            $table->foreign('id_edition')
                ->references('id')->on('editions')
                ->onDelete('cascade');

            $table->foreign('id_category')
                ->references('id')->on('categories')
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
        Schema::dropIfExists('editions_categories');
    }
}
