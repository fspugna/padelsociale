<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionCategoryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edition_category_types', function (Blueprint $table) {
            $table->unsignedInteger('id_edition');
            $table->unsignedInteger('id_category_type');
            $table->primary(['id_edition', 'id_category_type']);
            $table->timestamps();

            $table->foreign('id_edition')
                ->references('id')->on('editions')
                ->onDelete('cascade');

            $table->foreign('id_category_type')
                ->references('id')->on('category_types')
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
        Schema::dropIfExists('edition_category_types');
    }
}
