<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->unsignedBigInteger('post_pk');
            $table->unsignedBigInteger('user_pk');
            $table->text('content');
            $table->timestamps();
            
            $table->foreign('post_pk')
                ->references('pk')
                ->on('posts')
                ->onDelete('cascade');
            
            $table->foreign('user_pk')
                ->references('pk')
                ->on('users');
            
            $table->index('post_pk');
            $table->index('user_pk');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
