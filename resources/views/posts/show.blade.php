@extends('layouts.app')

@section('title', $post->title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
@endsection

@section('content')
    <div class="post-detail-page">
        <section class="post-detail-card">
            <div class="post-detail-top">
                <div class="post-breadcrumb">
                    <a href="{{ route('channels.show', $post->channel->pk) }}">
                        {{ $post->channel->name }}
                    </a>
                    <span>/</span>
                    <span>{{ $post->category ? $post->category->name : '미분류' }}</span>
                </div>
            
                <div class="post-top-actions">
                    <a href="{{ route('channels.show', $post->channel->pk) }}" class="post-back-link">
                        채널 게시판으로 돌아가기
                    </a>
            
                    @auth
                        @php
                            $canManage = false;
            
                            if ((int) auth()->user()->pk === (int) $post->user_pk) {
                                $canManage = true;
                            } elseif (auth()->user()->canManageChannel($post->channel)) {
                                $canManage = true;
                            }
                        @endphp
            
                        @if ($canManage)
                            <a href="{{ route('posts.edit', $post->pk) }}" class="btn-basic">수정</a>
            
                            <form method="POST" action="{{ route('posts.destroy', $post->pk) }}" class="post-delete-form" onsubmit="return confirm('게시글을 삭제하시겠습니까?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-basic">삭제</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="post-title-area">
                <h2>{{ $post->title }}</h2>
            </div>

            <div class="post-meta-row">
                <div class="post-meta-item">
                    <strong>작성자</strong>
                    <span>{{ $post->user ? $post->user->login_id : '-' }}</span>
                </div>
                <div class="post-meta-item">
                    <strong>작성일</strong>
                    <span>{{ $post->created_at ? $post->created_at->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="post-meta-item">
                    <strong>조회수</strong>
                    <span>{{ number_format($post->view_count) }}</span>
                </div>
                <div class="post-meta-item">
                    <strong>댓글 수</strong>
                    <span>{{ number_format($post->comments_count) }}</span>
                </div>
            </div>

            <div class="post-content-area">
                {!! nl2br(e($post->content)) !!}
            </div>
        </section>

        <section class="comment-card">
            <div class="comment-header">
                <h3>댓글</h3>
                <span>총 {{ number_format($post->comments_count) }}개</span>
            </div>
        
            @if ($errors->any())
                <div class="post-form-error-box" style="margin-top: 18px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            @if ($post->comments->isEmpty())
                <div class="comment-empty" style="margin-top: 18px;">
                    아직 등록된 댓글이 없습니다.
                </div>
            @else
                <div class="comment-list" style="margin-top: 18px;">
                   @foreach ($post->comments as $comment)
                        <article class="comment-item">
                            <div class="comment-meta">
                                <div class="comment-meta-left">
                                    <span class="comment-writer">
                                        {{ $comment->user ? $comment->user->login_id : '-' }}
                                    </span>
                                    <span class="comment-date">
                                        {{ $comment->created_at ? $comment->created_at->format('Y-m-d H:i') : '-' }}
                                    </span>
                                </div>
                    
                                @auth
                                    @php
                                        $canManageComment = false;
                    
                                        if ((int) auth()->user()->pk === (int) $comment->user_pk) {
                                            $canManageComment = true;
                                        } elseif (auth()->user()->canManageChannel($post->channel)) {
                                            $canManageComment = true;
                                        }
                                    @endphp
                                    
                                    @if ($canManageComment)
                                        <div class="comment-actions">
                                            <a
                                                href="{{ route('posts.show', array('postPk' => $post->pk, 'edit_comment_pk' => $comment->pk)) }}"
                                                class="comment-action-link"
                                            >수정</a>
                    
                                            <form
                                                method="POST"
                                                action="{{ route('comments.destroy', $comment->pk) }}"
                                                class="comment-delete-form"
                                                onsubmit="return confirm('댓글을 삭제하시겠습니까?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="comment-action-button">삭제</button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            
                            @if ((int) $editCommentPk === (int) $comment->pk)
                                <div class="comment-inline-edit-wrap">
                                    <form method="POST" action="{{ route('comments.update', $comment->pk) }}" class="comment-form">
                                        @csrf
                                        @method('PUT')
                    
                                        <div class="comment-form-field">
                                            <label for="edit_content_{{ $comment->pk }}">댓글 수정</label>
                                            <textarea
                                                name="content"
                                                id="edit_content_{{ $comment->pk }}"
                                                rows="4"
                                            >{{ old('content', $comment->content) }}</textarea>
                                        </div>
                    
                                        <div class="comment-form-actions">
                                            <button type="submit" class="btn-basic">수정</button>
                                            <a href="{{ route('posts.show', $post->pk) }}" class="btn-secondary" style="margin-left: 5px;">취소</a>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="comment-content">
                                    {!! nl2br(e($comment->content)) !!}
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        
            @auth
                <div class="comment-form-wrap" style="margin-top: 18px;">
                    <form method="POST" action="{{ route('comments.store', $post->pk) }}" class="comment-form">
                        @csrf
        
                        <div class="comment-form-field">
                            <label for="content">댓글 작성</label>
                            <textarea
                                name="content"
                                id="content"
                                rows="4"
                                placeholder="댓글을 입력하세요."
                            >{{ old('content') }}</textarea>
                        </div>
        
                        <div class="comment-form-actions">
                            <button type="submit" class="btn-basic">댓글 등록</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="comment-login-guide" style="margin-top: 18px;">댓글 작성은 로그인 후 이용할 수 있습니다.</div>
            @endauth
        </section>
    </div>
@endsection