<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'myBoard')</title>

    <style>
        html {
            overflow-y: scroll;
        }
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f8fb;
            color: #2f3a44;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .site-header {
            background-color: #dff1f8;
            border-bottom: 1px solid #c9dfe8;
        }
        .header-inner,
        .flash-wrap,
        .page-container {
            max-width: 1200px;
        }
        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        .header-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .header-search-placeholder {
            padding: 6px 12px;
            border-radius: 8px;
            background-color: #edf7fb;
            border: 1px solid #d7e7ee;
            font-size: 14px;
            color: #5c7885;
        }

        .site-logo a {
            font-size: 38px;
            font-weight: 700;
            color: #2d6f88;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .header-nav a,
        .header-nav button,
        .header-user {
            font-size: 15px;
        }

        .header-link {
            color: #3b6475;
        }

        .header-link:hover {
            color: #245160;
        }

        .header-user {
            color: #4f6470;
        }

        .logout-form {
            margin: 0;
        }

        .btn-basic {
            border: 1px solid #9fc8d8;
            background-color: #ffffff;
            color: #2d6f88;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-basic:hover {
            background-color: #f3fbfe;
        }

        .flash-wrap {
            max-width: 1200px;
            margin: 16px auto 0;
            padding: 0 24px;
        }

        .flash-message {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .flash-success {
            background-color: #edf9f0;
            border: 1px solid #b9e3c2;
            color: #2f6b39;
        }

        .flash-error {
            background-color: #fff0f0;
            border: 1px solid #f0c3c3;
            color: #8a3d3d;
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 24px 60px;
        }

        .content-card {
            background-color: #ffffff;
            border: 1px solid #d7e7ee;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(100, 140, 160, 0.08);
            padding: 24px;
        }
    </style>

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
                    <div class="header-search-placeholder">채널 검색</div>
                </nav>
            </div>

            <nav class="header-nav">
                @auth
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