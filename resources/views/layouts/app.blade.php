<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="株日記,日記,ノート,株,取引,記録,振り返り">
    <meta name="description" content="過去の株の取引を記録し振り返る。振り返ることで、自分の弱点が見えてくる。自分の取引を改善し、パフォーマンス向上！">
    
    <meta property="og:title" content="株日記">
    <meta property="og:description" content="過去の株の取引を記録し振り返る。振り返ることで、自分の弱点が見えてくる。自分の取引を改善し、パフォーマンス向上！">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:image" content="{{asset('storage/images/ogp.png')}}">
    <meta property="og:site_name" content="株日記">
    <meta property="og:locale" content="ja_JP">
    <!-- Twitterシェア時の表示形式指定 -->
    <meta name="twitter:card" content="summary_large_image">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{__('words.stock_diary')}}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Normalize -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" v-cloak>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('diary') }}">
                    {{__('words.stock_diary')}}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('company') }}">{{__('words.search_company')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('favorite') }}">{{__('words.company_diary')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('search') }}">{{__('words.search_diary')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('output') }}">{{__('words.output_diary')}}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 mt-5">
            @yield('content')
        </main>

        <footer id="footer-area" class="bg-dark text-light">
            <div class="row">
                <div class="col-md-3">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="/#feature" class="nav-link link-light">{{__('words.feature')}}</a></li>
                        <li class="nav-item"><a href="/#usage" class="nav-link link-light">{{__('words.usage')}}</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="{{route('terms')}}" class="nav-link link-light">{{__('words.terms_of_service')}}</a></li>
                        <li class="nav-item"><a href="{{route('policy')}}" class="nav-link link-light">{{__('words.privacy_policy')}}</a></li>
                        <li class="nav-item"><a href="{{route('contact')}}" class="nav-link link-light">{{__('words.contact')}}</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
