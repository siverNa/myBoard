@extends('layouts.app')

@section('title', 'myBoard 메인')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/channel.css') }}">
@endsection

@section('content')
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
                <span class="section-subtext">게시글 수 기준 상위 채널 6개를 표시합니다.</span>
            </div>

            @if ($channels->isEmpty())
                <div class="content-card home-empty">
                    현재 표시할 채널이 없습니다.
                </div>
            @else
                <div class="channel-grid">
                    @foreach ($channels as $channel)
                        @php
                            $posts = isset($latestPostsByChannel[$channel->pk]) ? $latestPostsByChannel[$channel->pk] : array();
                        @endphp

                        <article class="channel-card">
                            <div class="channel-card-header">
                                <h4>
                                    <a href="{{ route('channels.show', $channel->pk) }}">
                                        {{ $channel->name }}
                                    </a>
                                </h4>
                            </div>

                            <div class="channel-card-body">
                                @if (!empty($posts))
                                    <ul class="post-list">
                                        @foreach ($posts as $post)
                                            <li>
                                                <a href="{{ route('posts.show', $post->pk) }}">
                                                    {{ $post->title }}
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
            @endif
        </section>
    </div>
@endsection