<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_group');
            $table->string('name');
            $table->bigInteger('matchcode');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('id_group')
                ->references('id')->on('groups')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('rounds');
        Schema::enableForeignKeyConstraints();
    }
}
