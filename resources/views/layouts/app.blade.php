<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Qravel</title>

    <!-- Styles -->
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/header.css') }}" rel="stylesheet">
    
    <!--bootstrap-->
    <!--CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <!--JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{secure_asset('js/jquery.multi-select.js')}}"></script>
    <script src="{{secure_asset('js/jquery.quicksearch.js')}}"></script>
    
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/question_show.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/question_new.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/index.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/multi-select.css') }}" rel="stylesheet">

    <link href="{{ secure_asset('css/user_show.css') }}" rel="stylesheet">

    <link href="{{ secure_asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/register.css') }}" rel="stylesheet">


</head>
<body>
    @if(Auth::check())<!--ログイン時-->
    <header class="header">
      
      <nav class="nav">
        <ul class="header_menu">
          <li class="header_menu_title">
            <a class="nav-link questionNew" href="/">Qravel</a>
          </li>
          <li class="header_serch">
            <form action="/search" method="post">
               {{ csrf_field() }}
              <input class="search" type="search" name="key_word" placeholder="キーワードを入力">
              <button type="submit" name="submit">検索</button>
            </form>
          </li>
          <li class="header_menu_new">
              <!--ログイン時は質問投稿画面に--><a class="nav-link questionNew" href="/newQuestion">質問する</a>　
          </li>
          
          <ul class="header_menu_inner">
              <!--ログイン時のView-->
              <li class="user">
                  <a class="nav-link user-name" href="/users/show" onclick="">
                    {{ Auth::user()->name }}さん
                  </a>
              </li>
              <li>
                  <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  ログアウト
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                  </form>
              </li>
            </ul>
        </ul>
        
      </nav>
    
    </header>
    @else<!--未ログイン時-->
    <header class="header">
      <nav class="nav">
        <ul class="header_menu">
          <li class="header_menu_title">
              <a class="nav-link questionNew" href="/">Qravel</a>
          </li>
          <li class="header_serch">
            <form action="/search" method="post">
               {{ csrf_field() }}
              <input class="search" type="search" name="key_word" placeholder="キーワードを入力">
              <button type="submit" name="submit">検索</button>
            </form>
          </li>
          <li class="header_menu_new">
              <!--未ログイン時はログイン画面に--><a class="nav-link questionNew" href="{{ route('login') }}">質問する</a>
          </li>
          <ul class="header_menu_inner">
              <!--未ログイン時のView-->
              <li class="login">
                  <a class="nav-link" href="{{ route('login') }}" onclick="">
                    ログイン
                  </a>
              </li>
              <li class="sign-up">
                  <a class="nav-link" href="{{ route('register') }}" onclick="">
                    会員登録
                  </a>
              </li>
            </ul>
        </ul>
      </nav>
    </header>
    @endif
    @yield('content')
</body>
</html>
