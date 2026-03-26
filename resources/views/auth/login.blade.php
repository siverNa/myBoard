@extends('layouts.app')

@section('title', '로그인')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
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