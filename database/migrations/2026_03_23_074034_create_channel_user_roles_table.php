<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_user_roles', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->unsignedBigInteger('channel_pk');
            $table->unsignedBigInteger('user_pk');
            $table->string('role', 30)->default('manager');
            $table->timestamps();
            
            $table->foreign('channel_pk')
                ->references('pk')
                ->on('channels')
                ->onDelete('cascade');
            
            $table->foreign('user_pk')
                ->references('pk')
                ->on('users')
                ->onDelete('cascade');
            
            $table->unique(['channel_pk', 'user_pk']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_user_roles');
    }
}
