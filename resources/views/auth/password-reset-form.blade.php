@extends('layouts.app')

@section('title', '새 비밀번호 설정')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-card">
        <h2>새 비밀번호 설정</h2>

        @if ($errors->any())
            <div class="auth-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.password.reset') }}">
            @csrf

            <input type="hidden" name="login_id" value="{{ $loginId }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="auth-field">
                <label>아이디</label>
                <div class="auth-readonly-box">{{ $loginId }}</div>
            </div>
            
            <div class="auth-field">
                <label>이메일</label>
                <div class="auth-readonly-box">{{ $email }}</div>
            </div>

            <div class="auth-field">
                <label for="password">새 비밀번호</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="auth-field">
                <label for="password_confirmation">새 비밀번호 확인</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn-basic">비밀번호 변경</button>
                <a href="{{ route('login.form') }}" class="btn-secondary">취소</a>
            </div>
        </form>
    </div>
@endsection