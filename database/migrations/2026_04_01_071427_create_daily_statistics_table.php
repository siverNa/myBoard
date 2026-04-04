<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStatisticsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_statistics', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->date('stat_date')->unique()->comment('집계 기준 날짜');

            $table->unsignedInteger('total_active_channels')->default(0)->comment('활성 채널 수');
            $table->unsignedInteger('total_posts')->default(0)->comment('전체 게시글 수');
            $table->unsignedInteger('total_users')->default(0)->comment('전체 회원 수');
            $table->unsignedInteger('total_comments')->default(0)->comment('전체 댓글 수');

            $table->integer('diff_active_channels')->default(0)->comment('활성 채널 증감량');
            $table->integer('diff_posts')->default(0)->comment('게시글 증감량');
            $table->integer('diff_users')->default(0)->comment('회원 증감량');
            $table->integer('diff_comments')->default(0)->comment('댓글 증감량');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_statistics');
    }
}
