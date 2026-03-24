@extends('layouts.app')

@section('title', $channel->name)

@section('content')
    <div class="content-card">
        <h2>{{ $channel->name }}</h2>
        <p>{{ $channel->description ?: '채널 소개가 없습니다.' }}</p>
        <p>여기는 추후 채널 소개 / 카테고리 / 게시글 목록이 들어갈 채널 게시판 화면입니다.</p>
    </div>
@endsection