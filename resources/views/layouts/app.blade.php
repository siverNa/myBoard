<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'myBoard')</title>
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <div class="header-left">
                <div class="site-logo">
                    <a href="{{ route('home') }}">myBoard</a>
                </div>
            
                <nav class="header-menu">
                    <a href="{{ route('channels.index') }}" class="header-link">채널 목록</a>
                    <!-- 검색창은 나중에 구현 -->
                    <form method="GET" action="{{ route('channels.index') }}" class="header-search-form">
                        <input
                            type="text"
                            name="keyword"
                            class="header-search-input"
                            placeholder="채널 검색"
                            value="{{ request('keyword') }}"
                        >
                        <button type="submit" class="header-search-button">검색</button>
                    </form>
                </nav>
            </div>

            <nav class="header-nav">
                @auth
                    <a href="{{ route('channel-requests.create') }}" class="header-link">채널 개설 신청</a>
                
                    @if (auth()->user()->isSuperAdmin())
                        <a href="{{ route('channel-requests.index') }}" class="header-link">신청 관리</a>
                    @endif
                
                    <span class="header-user">
                        {{ auth()->user()->login_id }} 님
                    </span>
                
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-basic">로그아웃</button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}" class="header-link">로그인</a>
                    <a href="{{ route('register.form') }}" class="header-link">회원가입</a>
                @endauth
            </nav>
        </div>
    </header>

    <div class="flash-wrap">
        @if (session('success'))
            <div class="flash-message flash-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flash-message flash-error">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <main class="page-container">
        @yield('content')
    </main>
</body>
</html>