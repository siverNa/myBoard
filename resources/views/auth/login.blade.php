@extends('layouts.app')

@section('title', '로그인')

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
    }

    .auth-field input {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #cfe3eb;
        border-radius: 10px;
    }

    .auth-error {
        color: #b04848;
        margin-bottom: 14px;
    }
</style>
@endsection

@section('content')
    <div class="auth-card">
        <h2>로그인</h2>

        @if ($errors->any())
            <div class="auth-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-field">
                <label for="login_id">아이디</label>
                <input type="text" id="login_id" name="login_id" value="{{ old('login_id') }}">
            </div>

            <div class="auth-field">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password">
            </div>

            <button type="submit" class="btn-basic">로그인</button>
        </form>
    </div>
@endsection