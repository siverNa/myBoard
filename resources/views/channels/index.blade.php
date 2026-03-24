@extends('layouts.app')

@section('title', '채널 목록')

@section('styles')
<style>
    .channel-list-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .channel-list-header {
        background: linear-gradient(135deg, #eaf8fd 0%, #f9fdff 100%);
        border: 1px solid #d8ebf2;
        border-radius: 20px;
        padding: 28px;
    }

    .channel-list-header h2 {
        margin: 0 0 10px;
        font-size: 28px;
        color: #2d6f88;
    }

    .channel-list-header p {
        margin: 0;
        color: #5b7480;
        line-height: 1.6;
    }

    .channel-list-sort {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .channel-list-sort h3 {
        margin: 0;
        font-size: 22px;
        color: #355766;
    }

    .channel-list-sort span {
        font-size: 14px;
        color: #6e8792;
    }

    .channel-list-wrap {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .channel-row {
        display: block;
        background-color: #ffffff;
        border: 1px solid #d7e7ee;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(100, 140, 160, 0.08);
        padding: 20px 22px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .channel-row:hover {
        transform: translateY(-2px);
        background-color: #fcfeff;
        box-shadow: 0 10px 24px rgba(100, 140, 160, 0.12);
    }

    .channel-row-inner {
        display: grid;
        grid-template-columns: 220px 1fr 120px;
        gap: 18px;
        align-items: center;
    }

    .channel-name-box {
        background-color: #dff1f8;
        border: 1px solid #cfe3eb;
        border-radius: 12px;
        padding: 16px 18px;
    }

    .channel-name-box h4 {
        margin: 0;
        font-size: 22px;
        color: #2f6174;
    }

    .channel-desc-box {
        background-color: #f9fcfd;
        border: 1px solid #e2edf2;
        border-radius: 12px;
        padding: 16px 18px;
        color: #536973;
        line-height: 1.6;
        min-height: 72px;
        display: flex;
        align-items: center;
    }

    .channel-meta-box {
        text-align: right;
        color: #5f7884;
        font-size: 14px;
    }

    .channel-meta-count {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 999px;
        background-color: #edf7fb;
        border: 1px solid #d7e7ee;
        color: #2d6f88;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .channel-row-inner {
            grid-template-columns: 1fr;
        }

        .channel-meta-box {
            text-align: left;
        }
    }
</style>
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
                <h3>게시글 수 기준 인기 채널</h3>
                <span>활성화된 채널만 표시됩니다.</span>
            </div>

            @if ($channels->isEmpty())
                <div class="empty-channel-box">현재 생성된 채널이 없습니다.</div>
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