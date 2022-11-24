<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_tournament');
            $table->unsignedInteger('id_zone');
            $table->unsignedBigInteger('id_category');
            $table->unsignedInteger('id_category_type');         
            $table->tinyInteger('generated')->default(0);
            $table->tinyInteger('edit_mode')->default(0);
            $table->timestamps();

            $table->foreign('id_tournament')
                ->references('id')->on('tournaments')
                ->onDelete('cascade');

            $table->foreign('id_zone')
                ->references('id')->on('zones')
                ->onDelete('cascade');

            $table->foreign('id_category')
                ->references('id')->on('categories')
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
        Schema::dropIfExists('divisions');
    }
}
