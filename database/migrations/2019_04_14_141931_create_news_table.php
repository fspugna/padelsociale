<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_news_category');
            $table->string('title');
            $table->text('excerpt');
            $table->text('content');
            $table->string('image')->nullable();    
            $table->string('permalink');
            $table->tinyInteger('notified')->default(0);
            $table->tinyInteger('status')->nullable()->comment('0 bozza, 1 pubblicato');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
