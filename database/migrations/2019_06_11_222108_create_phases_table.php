<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_bracket');
            $table->integer("name");                        
            $table->bigInteger('matchcode');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['id_bracket', 'name']);

            $table->foreign('id_bracket')
                ->references('id')->on('brackets')
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
        Schema::dropIfExists('phases');
    }
}
