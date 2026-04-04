@extends('layouts.app')

@section('content')
    <div class="admin-statistic-page">
        <div class="admin-statistic-container">
            <div class="admin-statistic-header">
                <h1 class="admin-statistic-title">서비스 추이</h1>
                <p class="admin-statistic-description">
                    myBoard 서비스의 일별 운영 지표를 확인할 수 있습니다.
                </p>
            </div>

            @if ($latestStatistic)
                <section class="admin-statistic-section">
                    <h2 class="admin-statistic-section-title">최신 집계 요약</h2>

                    <div class="admin-statistic-summary-grid">
                        <div class="admin-statistic-card">
                            <div class="admin-statistic-card-label">집계일</div>
                            <div class="admin-statistic-card-value is-date">
                                {{ $latestStatistic->stat_date }}
                            </div>
                        </div>

                        <div class="admin-statistic-card">
                            <div class="admin-statistic-card-label">활성 채널 수</div>
                            <div class="admin-statistic-card-value">
                                {{ number_format($latestStatistic->total_active_channels) }}
                            </div>
                        </div>

                        <div class="admin-statistic-card">
                            <div class="admin-statistic-card-label">게시글 수</div>
                            <div class="admin-statistic-card-value">
                                {{ number_format($latestStatistic->total_posts) }}
                            </div>
                        </div>

                        <div class="admin-statistic-card">
                            <div class="admin-statistic-card-label">회원 수</div>
                            <div class="admin-statistic-card-value">
                                {{ number_format($latestStatistic->total_users) }}
                            </div>
                        </div>

                        <div class="admin-statistic-card">
                            <div class="admin-statistic-card-label">댓글 수</div>
                            <div class="admin-statistic-card-value">
                                {{ number_format($latestStatistic->total_comments) }}
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <section class="admin-statistic-section">
                <div class="admin-statistic-section-top">
                    <h2 class="admin-statistic-section-title">집계 실행</h2>

                    <form method="POST" action="{{ route('admin.statistics.run') }}">
                        @csrf
                        <button type="submit" class="admin-statistic-button">
                            집계 수동 실행
                        </button>
                    </form>
                </div>
            </section>

            <section class="admin-statistic-section">
                <h2 class="admin-statistic-section-title">일별 집계 목록</h2>

                <div class="admin-statistic-table-wrap">
                    <table class="admin-statistic-table">
                        <thead>
                            <tr>
                                <th>집계일</th>
                                <th>활성 채널 수</th>
                                <th>채널 증감</th>
                                <th>게시글 수</th>
                                <th>게시글 증감</th>
                                <th>회원 수</th>
                                <th>회원 증감</th>
                                <th>댓글 수</th>
                                <th>댓글 증감</th>
                                <th>생성일시</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($statistics as $statistic)
                                @php
                                    $channelDiffClass = $statistic->diff_active_channels > 0
                                        ? 'is-increase'
                                        : ($statistic->diff_active_channels < 0 ? 'is-decrease' : 'is-neutral');

                                    $postDiffClass = $statistic->diff_posts > 0
                                        ? 'is-increase'
                                        : ($statistic->diff_posts < 0 ? 'is-decrease' : 'is-neutral');

                                    $userDiffClass = $statistic->diff_users > 0
                                        ? 'is-increase'
                                        : ($statistic->diff_users < 0 ? 'is-decrease' : 'is-neutral');

                                    $commentDiffClass = $statistic->diff_comments > 0
                                        ? 'is-increase'
                                        : ($statistic->diff_comments < 0 ? 'is-decrease' : 'is-neutral');
                                @endphp

                                <tr>
                                    <td>{{ $statistic->stat_date }}</td>
                                    <td>{{ number_format($statistic->total_active_channels) }}</td>
                                    <td class="{{ $channelDiffClass }}">
                                        {{ $statistic->diff_active_channels > 0 ? '+' : '' }}{{ number_format($statistic->diff_active_channels) }}
                                    </td>
                                    <td>{{ number_format($statistic->total_posts) }}</td>
                                    <td class="{{ $postDiffClass }}">
                                        {{ $statistic->diff_posts > 0 ? '+' : '' }}{{ number_format($statistic->diff_posts) }}
                                    </td>
                                    <td>{{ number_format($statistic->total_users) }}</td>
                                    <td class="{{ $userDiffClass }}">
                                        {{ $statistic->diff_users > 0 ? '+' : '' }}{{ number_format($statistic->diff_users) }}
                                    </td>
                                    <td>{{ number_format($statistic->total_comments) }}</td>
                                    <td class="{{ $commentDiffClass }}">
                                        {{ $statistic->diff_comments > 0 ? '+' : '' }}{{ number_format($statistic->diff_comments) }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($statistic->created_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="admin-statistic-empty">
                                        집계 데이터가 없습니다.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
