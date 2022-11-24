<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile_phone');
            $table->string('password');
            $table->unsignedInteger('id_role');
            $table->unsignedInteger('id_city');
            $table->tinyInteger('status')->default(0)->comment('0: disabilitato - 1: attivo');
            $table->char('gender');            
            $table->unsignedInteger('position');
            $table->unsignedInteger('id_club');
            $table->string('lun');
            $table->string('mar');
            $table->string('mer');
            $table->string('gio');
            $table->string('ven');
            $table->string('sab');
            $table->string('dom');
            $table->string('note_disp');            
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_role')->references('id')->on('roles');            
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
