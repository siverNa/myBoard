<?php

namespace App\Console\Commands;

use App\Services\DailyStatisticService;
use Illuminate\Console\Command;

class DailySnapshotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:daily-snapshot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '일별 통계 데이터를 집계하여 저장합니다.';

    /**
     * @var DailyStatisticService
     */
    protected $dailyStatisticService;

    /**
     * Create a new command instance.
     *
     * @param DailyStatisticService $dailyStatisticService
     * @return void
     */
    public function __construct(DailyStatisticService $dailyStatisticService)
    {
        parent::__construct();

        $this->dailyStatisticService = $dailyStatisticService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $statistic = $this->dailyStatisticService->snapshot();

            $this->info('일별 통계 집계가 완료되었습니다.');
            $this->line('집계 날짜: ' . $statistic->stat_date);
            $this->line('활성 채널 수: ' . $statistic->total_active_channels);
            $this->line('게시글 수: ' . $statistic->total_posts);
            $this->line('회원 수: ' . $statistic->total_users);
            $this->line('댓글 수: ' . $statistic->total_comments);

            return 0;
        } catch (\Exception $e) {
            $this->error('일별 통계 집계 중 오류가 발생했습니다.');
            $this->error($e->getMessage());

            return 1;
        }
    }
}
