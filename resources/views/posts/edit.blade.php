@extends('layouts.app')

@section('title', '게시글 수정')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
@endsection

@section('content')
    <div class="post-form-page">
        <section class="post-form-card">
            <div class="post-form-header">
                <div>
                    <h2>게시글 수정</h2>
                    <p>
                        현재 채널: <strong>{{ $post->channel->name }}</strong>
                    </p>
                </div>

                <a href="{{ route('posts.show', $post->pk) }}" class="post-back-link">
                    게시글 상세로 돌아가기
                </a>
            </div>

            @if ($errors->any())
                <div class="post-form-error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('posts.update', $post->pk) }}" class="post-form">
                @csrf
                @method('PUT')

                <div class="post-form-field">
                    <label for="category_pk">카테고리</label>
                    <select name="category_pk" id="category_pk">
                        <option value="">카테고리를 선택하세요.</option>
                        @foreach ($post->channel->categories as $category)
                            <option value="{{ $category->pk }}" {{ (string) old('category_pk', $post->category_pk) === (string) $category->pk ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="post-form-field">
                    <label for="title">제목</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $post->title) }}"
                        maxlength="255"
                    >
                </div>

                <div class="post-form-field">
                    <label for="content">내용</label>
                    <textarea
                        name="content"
                        id="content"
                        rows="12"
                    >{{ old('content', $post->content) }}</textarea>
                </div>

                <div class="post-form-actions">
                    <button type="submit" class="btn-basic">수정하기</button>
                    <a href="{{ route('posts.show', $post->pk) }}" class="post-cancel-link">취소</a>
                </div>
            </form>
        </section>
    </div>
@endsection