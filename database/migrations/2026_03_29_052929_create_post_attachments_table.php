<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('post_attachments', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->unsignedBigInteger('post_pk');
            $table->string('original_name', 255);
            $table->string('stored_name', 255);
            $table->string('file_path', 500);
            $table->string('file_extension', 20);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();

            $table->index('post_pk', 'idx_post_attachments_post_pk');
            $table->foreign('post_pk', 'fk_post_attachments_post_pk')
                ->references('pk')
                ->on('posts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_attachments');
    }
}
