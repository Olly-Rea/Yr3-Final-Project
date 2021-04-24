@extends('layouts.app')

@section("styles")
<link href="{{ asset('css/welcome_page.css') }}" rel="stylesheet">
@endsection
@section('scripts-app')
<script src="{{ asset('js/welcome.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
@endsection

@section('title')Recipe App @endsection

@section('nav')
<div id="nav-left">
    <a href="@auth{{ route('lucky_dip') }}@else(){{ route('register') }}@endauth" id="site-logo">
        <svg>
            <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
        </svg>
        @auth
        <h3>Surprise me!</h3>
        @else
        <h3>Register</h3>
        @endauth
    </a>
</div>
<div id="site-links">
    @auth
    <a @if(Request::is('CookBook'))class="active"@else()href="{{ route('feed') }}"@endif><b>Home</b></a>
    <a href="{{ route('me') }}"><b>My Profile</b></a>
    <a @if(Request::is('TheAiChef'))class="active"@else()href="{{ route('ai_chef') }}"@endif><b>AI Chef</b><p class="beta">beta</p></a>
    @else
    <a @if(Request::is('register'))class="active" @else href="{{ route('register') }}"@endif><b>Sign Up!</b></a>
    <a @if(Request::is('login'))class="active"@else href="{{ route('login') }}"@endif><b>Login</b></a>
    @endauth
</div>
@endsection

@section('content')
<h1>Welcome to the 'Recipe App'!</h1>
<p>Your hub for all things food</p>
<div id="search-bar-container">
    <div>
        <input id="search-bar" type="text" name="search" placeholder="Search for something specific here!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to see results!'"/>
        <svg id="search-icon">
            <use xlink:href="{{ asset('images/graphics/search.svg#icon') }}"></use>
        </svg>
    </div>
    <div id="results-container" style="display: none"></div>
</div>
<h4>- Or -</h4>
@auth
<button id="show-feed">Take me home!</button>
@else
<button id="show-feed">Show me some ideas!</button>
@endauth
@endsection
