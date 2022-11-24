<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_edition');
            $table->unsignedInteger('id_tournament_type');
            $table->unsignedBigInteger('id_tournament_ref');
            $table->string('name');            
            $table->date("date_start");
            $table->date("date_end");
            $table->text('description')->nullable();
            $table->tinyInteger('generated')->default(0);

            $table->timestamps();
            $table->softDeletes();

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tournaments');
        Schema::enableForeignKeyConstraints();  
    }
}
