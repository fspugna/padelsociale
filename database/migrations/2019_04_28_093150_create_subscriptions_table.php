<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_team');
            $table->unsignedBigInteger('id_tournament');
            $table->unsignedInteger('id_zone');
            $table->unsignedInteger('id_category_type');
            $table->unsignedInteger('id_category')->nullable();
            $table->timestamps();

            $table->foreign('id_team')
                ->references('id')->on('teams')
                ->onDelete('cascade');
            
            $table->foreign('id_zone')
                ->references('id')->on('zones')
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
        Schema::dropIfExists('subscriptions');
    }
}
