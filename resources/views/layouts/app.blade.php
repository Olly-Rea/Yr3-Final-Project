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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        @yield ('jquery')
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @yield("scripts-app")
    </head>
    <body>
        @yield('nav')
        <main>
            @yield("content")
        </main>
        <div id="site-overlay" style="display: none">
            <div id="site-overlay-background"></div>
            @guest
                <div id="sign-up" class="prompt hidden" style="display: none">
                    <h1>Account required!</h1>
                    <p class="message"></p>
                    <button>Sign up now!</button>
                    <p class="close-prompt">Maybe later</p>
                </div>
            @endguest
            @yield('site-overlay')
        </div>
        <footer>
            <div id="footer-content">
                <p>{{ number_format(App\Models\Recipe::all()->count()) }} recipes in our database so far!</p>
                <a href="https://ollyrea.co.uk" target="_blank">Olly Rea - 950659</a>
            </div>
        </footer>
    </body>
</html>
