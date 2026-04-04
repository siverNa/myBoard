<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\DailyStatistic;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;

class DailyStatisticService
{
    /**
     * 특정 날짜 기준으로 일별 집계 데이터를 생성 또는 갱신한다.
     *
     * @param Carbon|null $date
     * @return DailyStatistic
     */
    public function snapshot(Carbon $date = null)
    {
        $date = $date ?: Carbon::today();
        $statDate = $date->toDateString();

        $totalActiveChannels = Channel::where('status', 'active')->count();
        $totalPosts = Post::count();
        $totalUsers = User::count();
        $totalComments = Comment::count();

        $previousStatistic = DailyStatistic::where('stat_date', '<', $statDate)
            ->orderBy('stat_date', 'desc')
            ->first();

        $diffActiveChannels = 0;
        $diffPosts = 0;
        $diffUsers = 0;
        $diffComments = 0;

        if ($previousStatistic) {
            $diffActiveChannels = $totalActiveChannels - (int) $previousStatistic->total_active_channels;
            $diffPosts = $totalPosts - (int) $previousStatistic->total_posts;
            $diffUsers = $totalUsers - (int) $previousStatistic->total_users;
            $diffComments = $totalComments - (int) $previousStatistic->total_comments;
        }

        return DailyStatistic::updateOrCreate(
            array(
                'stat_date' => $statDate,
            ),
            array(
                'total_active_channels' => $totalActiveChannels,
                'total_posts' => $totalPosts,
                'total_users' => $totalUsers,
                'total_comments' => $totalComments,
                'diff_active_channels' => $diffActiveChannels,
                'diff_posts' => $diffPosts,
                'diff_users' => $diffUsers,
                'diff_comments' => $diffComments,
            )
        );
    }
}
