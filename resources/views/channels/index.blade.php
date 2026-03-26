@extends('layouts.app')

@section('title', '채널 목록')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/channel.css') }}">
@endsection

@section('content')
    <div class="channel-list-page">
        <section class="channel-list-header content-card">
            <h2>채널 목록</h2>
            <p>
                게시글 수가 많은 순으로 채널을 확인할 수 있습니다.
                채널을 클릭하면 해당 채널 게시판으로 이동하게 됩니다.
            </p>
        </section>

        <section>
            <div class="channel-list-sort" style="margin-bottom: 14px;">
                @if (!empty($keyword))
                    <h3>"{{ $keyword }}" 검색 결과</h3>
                    <span>채널명 기준으로 검색한 결과입니다.</span>
                @else
                    <h3>게시글 수 기준 인기 채널</h3>
                    <span>활성화된 채널만 표시됩니다.</span>
                @endif
            </div>

            @if ($channels->isEmpty())
                <div class="empty-channel-box">
                    @if (!empty($keyword))
                        "{{ $keyword }}"에 대한 검색 결과가 없습니다.
                    @else
                        현재 생성된 채널이 없습니다.
                    @endif
                </div>
            @else
                <div class="channel-list-wrap">
                    @foreach ($channels as $channel)
                        <a href="{{ route('channels.show', $channel->pk) }}" class="channel-row">
                            <div class="channel-row-inner">
                                <div class="channel-name-box">
                                    <h4>{{ $channel->name }}</h4>
                                </div>

                                <div class="channel-desc-box">
                                    {{ $channel->description ?: '채널 소개가 아직 등록되지 않았습니다.' }}
                                </div>

                                <div class="channel-meta-box">
                                    <span class="channel-meta-count">
                                        게시글 {{ $channel->posts_count }}개
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection