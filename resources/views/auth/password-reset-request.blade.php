@extends('layouts.app')

@section('title', '비밀번호 재설정')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-card">
        <h2>비밀번호 재설정</h2>

        @if ($errors->any())
            <div class="auth-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.password.request') }}">
            @csrf

            <div class="auth-field">
                <label for="login_id">아이디</label>
                <input type="text" id="login_id" name="login_id" value="{{ old('login_id') }}">
            </div>

            <div class="auth-field">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn-basic">다음</button>
                <a href="{{ route('login.form') }}" class="btn-secondary">로그인으로</a>
            </div>
        </form>
    </div>
@endsection