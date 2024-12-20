<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Page title --}}
        <title>@yield("title")</title>
        <!-- Styles -->
        @yield("styles")
        <!-- Referenced JQuery scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @yield ('jquery')
        <!-- Scripts -->
        <script src="{{ secure_asset('js/app.js') }}" defer></script>
        @auth
        <script src="{{ secure_asset('js/auth.js') }}" defer></script>
        @else
        <script src="{{ secure_asset('js/guest.js') }}" defer></script>
        @endauth
        @yield("scripts-app")
    </head>
    <body>
        <nav>
            @yield('nav')
        </nav>
        <main>
            @yield("content")
        </main>
        <div id="notification-container">
            @if (session()->has("success"))
            <div class="success notify-panel">
                <h2>Success!</h2><h3>{{ session("success") }}</h3>
            </div>
            @endif
            @if ($errors->any())
            <div class="error notify-panel">
                <h2>Oh no!</h2><h3>An error occured: please check your input and try again</h3>
            </div>
            @endif
        </div>
        <div id="site-overlay" style="display: none">
            <div id="site-overlay-background"></div>
            @auth
                <div id="logout" class="prompt hidden" style="display: none">
                    <h1>Are you sure!</h1>
                    <p class="message">You are about to logout, proceed?</p>
                    <button id="logout-button" onclick="window.location.href='{{ route('logout') }}'; document.getElementById('logout-form').submit();">Log out</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>
                        @csrf
                    </form>
                    <p class="close-prompt">Not yet!</p>
                </div>
            @else
                <div id="sign-up" class="prompt hidden" style="display: none">
                    <h1>Account required!</h1>
                    <p class="message"></p>
                    <button>Sign up now!</button>
                    <p class="close-prompt">Maybe later</p>
                </div>
            @endauth
            @yield('site-overlay')
        </div>
        <footer>
            <div id="footer-content">
                <p>{{ App\Http\Controllers\RecipeController::count() }} recipes in our database so far!</p>
                <a href="https://ollyrea.co.uk" target="_blank">Olly Rea - 950659</a>
            </div>
        </footer>
    </body>
</html>
