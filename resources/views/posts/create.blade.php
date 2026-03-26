@extends('layouts.app')

@section('title', '게시글 작성')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
@endsection

@section('content')
    <div class="post-form-page">
        <section class="post-form-card">
            <div class="post-form-header">
                <div>
                    <h2>게시글 작성</h2>
                    <p>
                        현재 채널: <strong>{{ $channel->name }}</strong>
                    </p>
                </div>

                <a href="{{ route('channels.show', $channel->pk) }}" class="post-back-link">
                    채널 게시판으로 돌아가기
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

            <form method="POST" action="{{ route('posts.store', $channel->pk) }}" class="post-form">
                @csrf

                <div class="post-form-field">
                    <label for="category_pk">카테고리</label>
                    <select name="category_pk" id="category_pk">
                        <option value="">카테고리를 선택하세요.</option>
                        @foreach ($channel->categories as $category)
                            <option value="{{ $category->pk }}" {{ old('category_pk') == $category->pk ? 'selected' : '' }}>
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
                        value="{{ old('title') }}"
                        maxlength="255"
                    >
                </div>

                <div class="post-form-field">
                    <label for="content">내용</label>
                    <textarea
                        name="content"
                        id="content"
                        rows="12"
                    >{{ old('content') }}</textarea>
                </div>

                <div class="post-form-actions">
                    <button type="submit" class="btn-basic">등록하기</button>
                    <a href="{{ route('channels.show', $channel->pk) }}" class="post-cancel-link">취소</a>
                </div>
            </form>
        </section>
    </div>
@endsection