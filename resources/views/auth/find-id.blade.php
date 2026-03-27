@extends('layouts.app')

@section('title', '아이디 찾기')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-card">
        <h2>아이디 찾기</h2>

        @if ($errors->any())
            <div class="auth-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!empty($hasSearched))
            @if (!empty($foundLoginId))
                <div class="auth-result-box">
                    입력한 이메일로 가입된 아이디는 <strong>{{ $foundLoginId }}</strong> 입니다.
                </div>
            @else
                <div class="auth-result-box auth-result-box-error">
                    "{{ $searchedEmail }}" 로 가입된 계정을 찾을 수 없습니다.
                </div>
            @endif
        @endif

        <form method="POST" action="{{ route('auth.find-id') }}">
            @csrf

            <div class="auth-field">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" value="{{ old('email', isset($searchedEmail) ? $searchedEmail : '') }}">
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn-basic">아이디 찾기</button>
                <a href="{{ route('login.form') }}" class="btn-secondary">로그인으로</a>
            </div>
        </form>
    </div>
@endsection