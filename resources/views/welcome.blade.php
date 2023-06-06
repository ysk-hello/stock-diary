<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="株日記,日記,ノート,株,取引,記録,振り返り">
        <meta name="description" content="過去の株の取引を記録し振り返る。振り返ることで、自分の弱点が見えてくる。自分の取引を改善し、パフォーマンス向上！">
        
        <meta property="og:title" content="株日記">
        <meta property="og:description" content="過去の株の取引を記録し振り返る。振り返ることで、自分の弱点が見えてくる。自分の取引を改善し、パフォーマンス向上！">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{url()->current()}}">
        <meta property="og:image" content="{{asset('storage/images/ogp.png')}}">
        <meta property="og:site_name" content="株日記">
        <meta property="og:locale" content="ja_JP">
        <!-- Twitterシェア時の表示形式指定 -->
        <meta name="twitter:card" content="summary_large_image">

        <title>{{__('words.stock_diary')}}</title>

        <!-- favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <!-- Normalize -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{asset('css/style.css')}}">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        
    </head>
    <body>
        <header>
            <div id="header-area">
                <h1>{{__('words.stock_diary')}}</h1>
                <p>{{__('words.diary_desc')}}</p>
            </div>
        </header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
            <div class="container">
                <a href="/" class="navbar-brand">{{__('words.stock_diary')}}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-content">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#feature" class="nav-link active">{{__('words.feature')}}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#usage" class="nav-link">{{__('words.usage')}}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('register')}}" class="nav-link">{{ __('Register') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('login')}}" class="nav-link">{{ __('Login') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main id="main-area">
            <section id="feature" class="bg-light border">
                <h2>{{__('words.feature')}}</h2>
                <div class="feature">
                    <div class="row justify-content-center">
                        <div class="col-md-2">
                            <img src="{{asset('storage/images/paperless.png')}}" alt="paperless" id="paperless-img">
                        </div>
                        <div class="col-md-2">
                            <h5>{{__('words.paperless')}}</h5>
                            <p>{{__('words.paperless_desc')}}</p>
                        </div>
                        <div class="col-md-2">
                            <img src="{{asset('storage/images/review.png')}}" alt="review" id="review-img">
                        </div>
                        <div class="col-md-2">
                            <h5>{{__('words.review')}}</h5>
                            <p>{{__('words.review_desc')}}</p>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-2">
                            <img src="{{asset('storage/images/diary.png')}}" alt="diary" id="diary-img">
                        </div>
                        <div class="col-md-2">
                            <h5>{{__('words.company_diary')}}</h5>
                            <p>{{__('words.company_diary_desc')}}</p>
                        </div>
                        <div class="col-md-2">
                            <img src="{{asset('storage/images/csv.png')}}" alt="csv" id="output-img">
                        </div>
                        <div class="col-md-2">
                            <h5>{{__('words.file_output')}}</h5>
                            <p>{{__('words.file_output_desc')}}</p>
                        </div>
                    </div>
                </div>
            </section>
            <section id="usage" class="border">
                <h2>{{__('words.usage')}}</h2>
                <p>{{__('words.usage1')}}</p>
                <p>{{__('words.usage2')}}</p>
                <p>{{__('words.usage3')}}</p>

                <video src="{{asset('/storage/movies/usage.mp4')}}" controls></video>
            </section>
            <section id="register" class="bg-light border">
                <h2>{{__('words.start_diary')}}</h2>
                <p></p>
                <a href="{{route('register')}}" class="btn btn-primary">{{ __('Register') }}</a>
            </section>
        </main>
        <footer id="footer-area" class="bg-dark text-light">
            <div class="row">
                <div class="col-md-3">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="#feature" class="nav-link link-light">{{__('words.feature')}}</a></li>
                        <li class="nav-item"><a href="#usage" class="nav-link link-light">{{__('words.usage')}}</a></li>
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
    </body>
</html>
