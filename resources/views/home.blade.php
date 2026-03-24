@extends('layouts.app')

@section('title', 'myBoard 메인')

@section('styles')
<style>
    .home-wrapper {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .home-intro {
        background: linear-gradient(135deg, #eaf8fd 0%, #f9fdff 100%);
        border: 1px solid #d8ebf2;
        border-radius: 20px;
        padding: 28px;
    }

    .home-intro h2 {
        margin: 0 0 12px;
        font-size: 28px;
        color: #2d6f88;
    }

    .home-intro p {
        margin: 0;
        color: #55707d;
        line-height: 1.6;
    }

    .section-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .section-title-row h3 {
        margin: 0;
        font-size: 24px;
        color: #355766;
    }

    .section-subtext {
        font-size: 14px;
        color: #6e8792;
    }

    .channel-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .channel-card {
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        border: 1px solid #d7e7ee;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(100, 140, 160, 0.08);
    }

    .channel-card-header {
        background-color: #dff1f8;
        padding: 14px 16px;
        border-bottom: 1px solid #cfe3eb;
    }
    
    .channel-card-header h4 {
        margin: 0;
        font-size: 18px;
        color: #2f6174;
    }
    
    .channel-card-body {
        flex-grow: 1;
        padding: 14px 16px 16px;
        background-color: #ffffff;
        min-height: 220px;
    }
    
    .post-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .post-list li + li {
        margin-top: 8px;
    }
    
    .post-list a {
        display: block;
        padding: 8px 10px;
        background-color: #f7fbfd;
        border: 1px solid #e1eef3;
        border-radius: 8px;
        color: #405761;
        font-size: 14px;
        line-height: 1.35;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .post-list a:hover {
        background-color: #eef8fb;
        transform: translateY(-1px);
    }

    .home-empty {
        padding: 18px;
        text-align: center;
        color: #6e8792;
        background-color: #fbfeff;
        border: 1px dashed #cfe3eb;
        border-radius: 14px;
    }

    @media (max-width: 1024px) {
        .channel-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .channel-grid {
            grid-template-columns: 1fr;
        }

        .home-intro h2 {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')
    @php
        $sampleChannels = [
            [
                'name' => '자유게시판',
                'posts' => [
                    '오늘 점심 뭐 먹을지 추천해주세요',
                    '최근 본 영화 후기 남깁니다',
                    '출근길에 있었던 소소한 일',
                ],
            ],
            [
                'name' => '개발 공부',
                'posts' => [
                    'Laravel에서 middleware를 이해해보자',
                    'Eloquent relation 정리',
                    'Docker 기본 개념 다시 보기',
                ],
            ],
            [
                'name' => '프로젝트 공유',
                'posts' => [
                    'myBoard 메인 구조 초안 공유',
                    '게시글 삭제 정책 정리',
                    '채널 권한 구조 고민',
                ],
            ],
            [
                'name' => '질문과 답변',
                'posts' => [
                    'PHP 8과 7의 차이점이 뭘까?',
                    'Blade 레이아웃 분리 질문',
                    '로그인 세션 처리 방식 질문',
                ],
            ],
            [
                'name' => '공지사항',
                'posts' => [
                    '서비스 점검 안내',
                    '채널 운영 정책 변경 공지',
                    '게시판 이용 가이드',
                ],
            ],
            [
                'name' => '취미 공유',
                'posts' => [
                    '주말에 다녀온 카페 추천',
                    '최근 듣는 음악 공유',
                    '운동 루틴 기록',
                    '운동 루틴 기록',
                    '운동 루틴 기록',
                ],
            ],
        ];
    @endphp

    <div class="home-wrapper">
        <section class="home-intro content-card">
            <h2>채널 기반 게시판 서비스, myBoard</h2>

            @auth
                <p>
                    {{ auth()->user()->login_id }} 님, 환영합니다.
                    채널을 둘러보고 새로운 게시글을 확인해보세요.
                </p>
            @else
                <p>
                    다양한 채널의 최신 게시글을 둘러볼 수 있는 게시판 서비스입니다.
                    로그인하면 게시글 작성, 댓글 작성, 채널 개설 신청 기능을 사용할 수 있습니다.
                </p>
            @endauth
        </section>

        <section>
            <div class="section-title-row" style="margin-bottom: 16px;">
                <h3>인기 채널 및 최신 게시글</h3>
                <span class="section-subtext">현재는 샘플 데이터로 구성된 메인 화면입니다.</span>
            </div>

            <div class="channel-grid">
                @foreach ($sampleChannels as $channel)
                    <article class="channel-card">
                        <div class="channel-card-header">
                            <h4>{{ $channel['name'] }}</h4>
                        </div>

                        <div class="channel-card-body">
                            @if (!empty($channel['posts']))
                                <ul class="post-list">
                                    @foreach ($channel['posts'] as $postTitle)
                                        <li>
                                            <a href="javascript:void(0)">
                                                {{ $postTitle }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="home-empty">
                                    아직 게시글이 없습니다.
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection