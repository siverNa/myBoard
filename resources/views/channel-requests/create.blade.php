@extends('layouts.app')

@section('title', '채널 개설 신청')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/channel-request.css') }}">
@endsection

@section('content')
    <div class="channel-request-page">
        <section class="channel-request-card">
            <div class="channel-request-header">
                <h2>채널 개설 신청</h2>
                <p>채널명, 소개, 개설 이유를 입력하면 최고 관리자가 검토 후 승인합니다.</p>
            </div>

            @if ($errors->any())
                <div class="channel-request-error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('channel-requests.store') }}" class="channel-request-form">
                @csrf

                <div class="channel-request-field">
                    <label for="channel_name">채널명</label>
                    <input type="text" id="channel_name" name="channel_name" value="{{ old('channel_name') }}">
                </div>

                <div class="channel-request-field">
                    <label for="channel_description">채널 소개</label>
                    <textarea id="channel_description" name="channel_description" rows="4">{{ old('channel_description') }}</textarea>
                </div>

                <div class="channel-request-field">
                    <label for="reason">개설 이유</label>
                    <textarea id="reason" name="reason" rows="6">{{ old('reason') }}</textarea>
                </div>

                <div class="channel-request-actions">
                    <button type="submit" class="btn-basic">신청하기</button>
                    <a href="{{ route('home') }}" class="btn-secondary">취소</a>
                </div>
            </form>
        </section>
    </div>
@endsection