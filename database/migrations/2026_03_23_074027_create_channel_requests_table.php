<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_requests', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->unsignedBigInteger('applicant_user_pk');
            $table->string('channel_name', 191);
            $table->text('channel_description')->nullable();
            $table->text('reason');
            $table->string('status', 20)->default('pending');
            $table->unsignedBigInteger('reviewed_user_pk')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('applicant_user_pk')
                ->references('pk')
                ->on('users');
            
            $table->foreign('reviewed_user_pk')
                ->references('pk')
                ->on('users');
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_requests');
    }
}
