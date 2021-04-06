<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Page title --}}
        @yield("title")
        {{-- Styles --}}
        <link href="{{ asset('css/global.css') }}" rel="stylesheet">
        @yield("styles")
        {{-- Scripts --}}
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        <nav>
            <form id="search-bar-container" action="">
                <svg id="search-icon">
                    <use xlink:href="{{ asset('images/search.svg#icon') }}"></use>
                </svg>
                <input id="search-bar" type="text" name="search" placeholder="I'm looking for..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'I\'m looking for...'" />
            </form>
            <svg id="site-logo">
                <use xlink:href="{{ asset('images/logo.svg#icon') }}"></use>
            </svg>
        </nav>
        <main>
            @yield("content")
        </main>
        <footer>
            <a href="https://ollyrea.co.uk" target="_blank">Olly Rea - 950659</a>
        </footer>
    </body>
</html>

{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.js" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html> --}}
