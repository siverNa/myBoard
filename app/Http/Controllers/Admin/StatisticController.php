<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyStatistic;
use App\Services\DailyStatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * @var DailyStatisticService
     */
    protected $dailyStatisticService;

    /**
     * @param DailyStatisticService $dailyStatisticService
     */
    public function __construct(DailyStatisticService $dailyStatisticService)
    {
        $this->dailyStatisticService = $dailyStatisticService;
    }

    /**
     * 통계 추이 화면
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin($request);

        $statistics = DailyStatistic::orderBy('stat_date', 'desc')
            ->paginate(30);

        $latestStatistic = DailyStatistic::orderBy('stat_date', 'desc')->first();

        return view('admin.statistics.index', array(
            'statistics' => $statistics,
            'latestStatistic' => $latestStatistic,
        ));
    }

    /**
     * 수동 집계 실행
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runSnapshot(Request $request)
    {
        $this->authorizeSuperAdmin($request);

        $this->dailyStatisticService->snapshot();

        return redirect()->route('admin.statistics.index')
            ->with('success', '일별 통계 집계가 실행되었습니다.');
    }

    /**
     * 최고관리자 권한 체크
     *
     * @param Request $request
     * @return void
     */
    protected function authorizeSuperAdmin(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->global_role !== 'super_admin') {
            abort(403, '접근 권한이 없습니다.');
        }
    }
}
