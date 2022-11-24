<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');            
            $table->unsignedInteger('id_city');
            $table->unsignedBigInteger('id_user');
            $table->string('name');
            $table->string('address');            
            $table->string('phone');
            $table->string('mobile_phone');
            $table->text('description');
            $table->timestamps();

            $table->foreign('id_city')
                ->references('id')->on('cities')
                ->onDelete('cascade');

            $table->foreign('id_user')
                ->references('id')->on('users')
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
        Schema::dropIfExists('clubs');
    }
}
