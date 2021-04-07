@extends('layouts.app')

@section("styles")
<link href="{{ asset('css/welcome_page.css') }}" rel="stylesheet">
@endsection

@section('title')Recipe App @endsection

@section('nav')
<nav>
    <div id="nav-left">
        <svg id="site-logo">
            <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
        </svg>
    </div>
    <div id="site-links">
        <a @if(Request::is('IdeasBoard'))class="active"@else href="{{ route('feed') }}"@endif><b>Home</b></a>
        <a @if(Request::is('register'))class="active" @else href="{{ route('register') }}"@endif><b>Sign Up!</b></a>
        <a @if(Request::is('login'))class="active"@else href="{{ route('login') }}"@endif><b>Login</b></a>
    </div>
</nav>
@endsection

@section('content')
<h1>Welcome!</h1>

<h3>What are you in the mood for today?</h3>

<form id="search-bar-container" action="">
    <svg id="search-icon">
        <use xlink:href="{{ asset('images/search.svg#icon') }}"></use>
    </svg>
    <input id="search-bar" type="text" name="search" placeholder="Start your culinary search here!"
        onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start your culinary search here!'" />
</form>
@endsection
