@extends('layouts.app')

@section('scripts-app')
<script src="{{ asset('js/search.js') }}" defer></script>
@yield('scripts')
@endsection

@section('nav')
<div id="nav-left">
    <a href="@auth{{ route('lucky_dip') }}@else()/@endauth" id="site-logo">
        <svg>
            <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
        </svg>
        @auth
        <h3>Surprise me!</h3>
        @else
        <h3>Home</h3>
        @endauth
    </a>
    <div id="search-bar-container">
        <div id="results-container" style="display: none"></div>
        <input id="search-bar" type="text" name="search" placeholder="I'm looking for..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'I\'m looking for...'"/>
        <svg id="search-icon">
            <use xlink:href="{{ asset('images/graphics/search.svg#icon') }}"></use>
        </svg>
    </div>
</div>
<div id="site-links">
    <a @if(Request::is('CookBook'))class="active"@else()href="{{ route('feed') }}"@endif><b>Home</b></a>
    @auth
    <a @if(Request::is('Me'))class="active"@else()href="{{ route('me') }}"@endif><b>My Profile</b></a>
    @else
    <a href="{{ route('register') }}"><b>Sign Up!</b></a>
    @endauth
    @auth
    <a @if(Request::is('TheAiChef'))class="active"@else()href="{{ route('ai_chef') }}"@endif><b>AI Chef</b><p class="beta">beta</p></a>
    @else
    <a id="require-register"><b>AI Chef</b><p class="beta">beta</p></a>
    @endauth
</div>
@endsection
