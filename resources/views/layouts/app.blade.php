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
        <div id="site-overlay">
            @yield('site-overlay')
        </div>
        @yield('nav')
        <main>
            @yield("content")
        </main>
        <footer>
            <a href="https://ollyrea.co.uk" target="_blank">Olly Rea - 950659</a>
        </footer>
    </body>
</html>
