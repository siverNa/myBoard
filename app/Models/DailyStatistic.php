<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    protected $table = 'daily_statistics';

    protected $primaryKey = 'pk';

    protected $fillable = [
        'stat_date',
        'total_active_channels',
        'total_posts',
        'total_users',
        'total_comments',
        'diff_active_channels',
        'diff_posts',
        'diff_users',
        'diff_comments',
    ];
}
