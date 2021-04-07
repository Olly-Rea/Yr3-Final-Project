@extends('layouts.app')

@section('scripts-app')
<script src="{{ asset('js/search.js') }}" defer></script>
@yield('scripts')
@endsection

@section('nav')
<nav>
    <div id="nav-left">
        <svg id="site-logo">
            <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
        </svg>
        <form id="search-bar-container" action="">
            <svg id="search-icon">
                <use xlink:href="{{ asset('images/graphics/search.svg#icon') }}"></use>
            </svg>
            <input id="search-bar" type="text" name="search" placeholder="I'm looking for..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'I\'m looking for...'"/>
            <div id="results-container"></div>
        </form>
    </div>
    <div id="site-links">
        <a @if(Request::is('IdeasBoard'))class="active"@else()href="{{ route('feed') }}"@endif><b>Home</b></a>
        @auth
        <a href="{{ route('me') }}"><b>My Profile</b></a>
        @else
        <a href="{{ route('register') }}"><b>Sign Up!</b></a>
        @endauth
        <a @if(Request::is('TheAiChef'))class="active"@else()href="{{ route('ai_chef') }}"@endif><b>AI Chef</b><p class="beta">beta</p></a>
    </div>
</nav>
@endsection
