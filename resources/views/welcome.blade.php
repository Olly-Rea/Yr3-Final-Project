@extends('layouts.app')

@section("styles")
<link href="{{ asset('css/welcome_page.css') }}" rel="stylesheet">
@endsection

@section('title')Recipe App @endsection

@section('nav')
<nav>
    <div id="nav-left">
        <a href="@auth{{ route('lucky_dip') }}@else(){{ route('register') }}@endauth" id="site-logo">
            <svg>
                <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
            </svg>
        </a>
    </div>
    <div id="site-links">
        @auth
        <a @if(Request::is('IdeasBoard'))class="active"@else()href="{{ route('feed') }}"@endif><b>Home</b></a>
        <a href="{{ route('me') }}"><b>My Profile</b></a>
        <a @if(Request::is('TheAiChef'))class="active"@else()href="{{ route('ai_chef') }}"@endif><b>AI Chef</b><p class="beta">beta</p></a>
        @else
        <a @if(Request::is('register'))class="active" @else href="{{ route('register') }}"@endif><b>Sign Up!</b></a>
        <a @if(Request::is('login'))class="active"@else href="{{ route('login') }}"@endif><b>Login</b></a>
        @endauth
    </div>
</nav>
@endsection

@section('content')
<h1>Welcome to the 'Recipe App'!</h1>

{{-- <div id="quick-launch-categories">
    @foreach ($categories as $category)
    <div class="graphic-circle">
        <h3>{{ $category->name }}</h3>
    </div>
    @endforeach
</div> --}}

<h3>What are you in the mood for today?</h3>
<form id="search-bar-container" action="">
    <svg id="search-icon">
        <use xlink:href="{{ asset('images/graphics/search.svg#icon') }}"></use>
    </svg>
    <input id="search-bar" type="text" name="search" placeholder="Start your culinary search here!"
        onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start your culinary search here!'" />
</form>

<h4>- Or -</h4>

<button id="show-feed">Give me a taste</button>
@endsection
