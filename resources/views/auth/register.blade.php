@extends('layouts.app')

@section('title', '회원가입')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-card">
        <h2>회원가입</h2>

        @if ($errors->any())
            <div class="auth-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="auth-field">
                <label for="login_id">아이디</label>
                <input
                    type="text"
                    id="login_id"
                    name="login_id"
                    value="{{ old('login_id') }}"
                >
            </div>

            <div class="auth-field">
                <label for="email">이메일</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                >
            </div>

            <div class="auth-field">
                <label for="password">비밀번호</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                >
            </div>

            <div class="auth-field">
                <label for="password_confirmation">비밀번호 확인</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                >
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn-basic">회원가입</button>
                <a href="{{ route('login.form') }}" class="auth-sub-link">이미 계정이 있나요?</a>
            </div>
        </form>
    </div>
@endsection