@extends('layouts.app')

@section('title', '채널 개설 신청 관리')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/channel-request.css') }}">
@endsection

@section('content')
    <div class="channel-request-page">
        <section class="channel-request-card">
            <div class="channel-request-header">
                <h2>채널 개설 신청 관리</h2>
                <p>최고 관리자가 채널 개설 신청을 검토하고 승인 또는 반려할 수 있습니다.</p>
            </div>

            @if ($channelRequests->isEmpty())
                <div class="channel-request-empty">
                    현재 등록된 채널 개설 신청이 없습니다.
                </div>
            @else
                <div class="channel-request-list">
                    @foreach ($channelRequests as $channelRequest)
                        <article class="channel-request-item">
                            <div class="channel-request-item-top">
                                <div>
                                    <h3>{{ $channelRequest->channel_name }}</h3>
                                    <div class="channel-request-meta">
                                        신청자:
                                        {{ $channelRequest->applicant ? $channelRequest->applicant->login_id : '-' }}
                                    </div>
                                </div>

                                <span class="channel-request-status status-{{ $channelRequest->status }}">
                                    {{ $channelRequest->getStatusLabel() }}
                                </span>
                            </div>

                            <div class="channel-request-block">
                                <strong>채널 소개</strong>
                                <p>{{ $channelRequest->channel_description ? $channelRequest->channel_description : '-' }}</p>
                            </div>

                            <div class="channel-request-block">
                                <strong>개설 이유</strong>
                                <p>{{ $channelRequest->reason }}</p>
                            </div>

                            @if ($channelRequest->status === \App\Models\ChannelRequest::STATUS_REJECTED)
                                <div class="channel-request-block">
                                    <strong>반려 사유</strong>
                                    <p>{{ $channelRequest->reject_reason ? $channelRequest->reject_reason : '-' }}</p>
                                </div>
                            @endif

                            @if ($channelRequest->status === \App\Models\ChannelRequest::STATUS_PENDING)
                                <div class="channel-request-actions admin-actions">
                                    <form method="POST" action="{{ route('channel-requests.approve', $channelRequest->pk) }}">
                                        @csrf
                                        <button type="submit" class="btn-basic">승인</button>
                                    </form>

                                    <form method="POST" action="{{ route('channel-requests.reject', $channelRequest->pk) }}" class="channel-reject-form">
                                        @csrf
                                        <input type="text" name="reject_reason" placeholder="반려 사유 입력">
                                        <button type="submit" class="btn-secondary">반려</button>
                                    </form>
                                </div>
                            @else
                                <div class="channel-request-meta">
                                    검토자:
                                    {{ $channelRequest->reviewer ? $channelRequest->reviewer->login_id : '-' }}
                                    /
                                    검토일:
                                    {{ $channelRequest->reviewed_at ? $channelRequest->reviewed_at->format('Y-m-d H:i') : '-' }}
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection