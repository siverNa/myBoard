@extends('layouts.app')

@section('title', '회원가입')

@section('styles')
<style>
    .auth-card {
        max-width: 520px;
        margin: 0 auto;
        background-color: #ffffff;
        border: 1px solid #d7e7ee;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(100, 140, 160, 0.08);
        padding: 28px;
    }

    .auth-card h2 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #2d6f88;
    }

    .auth-field {
        margin-bottom: 14px;
    }

    .auth-field label {
        display: block;
        margin-bottom: 6px;
        color: #4a6470;
        font-size: 14px;
    }

    .auth-field input {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #cfe3eb;
        border-radius: 10px;
        font-size: 14px;
        color: #2f3a44;
        background-color: #ffffff;
    }

    .auth-field input:focus {
        outline: none;
        border-color: #8fc2d4;
        box-shadow: 0 0 0 3px rgba(143, 194, 212, 0.15);
    }

    .auth-error {
        color: #b04848;
        margin-bottom: 14px;
        font-size: 14px;
    }

    .auth-error ul {
        margin: 0;
        padding-left: 18px;
    }

    .auth-actions {
        margin-top: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .auth-sub-link {
        font-size: 14px;
        color: #5c7885;
    }

    .auth-sub-link:hover {
        color: #2d6f88;
    }
</style>
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