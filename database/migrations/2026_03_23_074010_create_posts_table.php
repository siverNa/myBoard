<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->unsignedBigInteger('channel_pk');
            $table->unsignedBigInteger('category_pk');
            $table->unsignedBigInteger('user_pk');
            $table->string('title', 191);
            $table->longText('content');
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
            
            $table->foreign('channel_pk')
                ->references('pk')
                ->on('channels')
                ->onDelete('cascade');
            
            $table->foreign('category_pk')
                ->references('pk')
                ->on('categories');
            
            $table->foreign('user_pk')
                ->references('pk')
                ->on('users');
            
            $table->index('channel_pk');
            $table->index('category_pk');
            $table->index('user_pk');
            $table->index('is_hidden');
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
        Schema::dropIfExists('posts');
    }
}
