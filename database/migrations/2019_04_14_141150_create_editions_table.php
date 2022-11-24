<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editions', function (Blueprint $table) {
            $table->increments('id');

            $table->string("edition_name");
            $table->tinyInteger('edition_type')->default(0)->comment('0: normale, 1: a squadre');
            $table->text("edition_description")->nullable();
            $table->text("edition_rules")->nullable();
            $table->text("edition_zone_rules")->nullable();
            $table->text("edition_awards")->nullable();
            $table->text("edition_zones_and_clubs")->nullable();          
            $table->string("logo")->nullable();
            $table->string("subscription_fee")->nullable();
            $table->timestamps();            
            $table->softDeletes();

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
        Schema::dropIfExists('editions');
        Schema::enableForeignKeyConstraints();
    }
}
