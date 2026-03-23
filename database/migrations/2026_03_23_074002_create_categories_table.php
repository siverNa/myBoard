<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->unsignedBigInteger('channel_pk');
            $table->string('name', 191);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('channel_pk')
                ->references('pk')
                ->on('channels')
                ->onDelete('cascade');
            
            $table->unique(['channel_pk', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
