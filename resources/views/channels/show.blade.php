@extends('layouts.app')

@section('title', $channel->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/channel.css') }}">
@endsection

@section('content')
    <div class="channel-show-page">
        <section class="channel-info-card">
            <div class="channel-info-row">
                <div class="channel-name-box">
                    <h2>{{ $channel->name }}</h2>
                </div>

                <div class="channel-desc-box">
                    {{ $channel->description ?: '채널 소개가 아직 등록되지 않았습니다.' }}
                </div>
            </div>
        </section>

        <section class="category-section">
            <div class="category-tabs">
                <a
                    href="{{ route('channels.show', $channel->pk) }}"
                    class="category-tab {{ empty($selectedCategoryPk) ? 'active' : '' }}"
                >
                    전체
                </a>

                @foreach ($channel->categories as $category)
                    <a
                        href="{{ route('channels.show', ['channelPk' => $channel->pk, 'category_pk' => $category->pk]) }}"
                        class="category-tab {{ (string)$selectedCategoryPk === (string)$category->pk ? 'active' : '' }}"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>

        <section class="post-board-card">
            <div class="post-board-header">
            <div>
                <h3>게시글 목록</h3>
                <span class="post-board-meta">최신 글 순으로 표시됩니다.</span>
            </div>
            @auth
                <a href="{{ route('posts.create', $channel->pk) }}" class="btn-basic">글쓰기</a>
            @endauth
        </div>

            @if ($posts->isEmpty())
                <div class="empty-post-box">
                    현재 등록된 게시글이 없습니다.
                </div>
            @else
                <div class="post-table-wrap">
                    <table class="post-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">글 번호</th>
                                <th style="width: 110px;">카테고리</th>
                                <th>제목</th>
                                <th style="width: 90px;">댓글 수</th>
                                <th style="width: 120px;">작성자</th>
                                <th style="width: 170px;">작성시간</th>
                                <th style="width: 90px;">조회수</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->pk }}</td>
                                    <td>{{ $post->category ? $post->category->name : '-' }}</td>
                                    <td class="post-title-cell">
                                        <a href="{{ route('posts.show', $post->pk) }}" class="post-title-link">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td>{{ $post->comments_count }}</td>
                                    <td>{{ $post->user ? $post->user->login_id : '-' }}</td>
                                    <td>{{ $post->created_at ? $post->created_at->format('Y-m-d H:i') : '-' }}</td>
                                    <td>{{ number_format($post->view_count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $posts->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection