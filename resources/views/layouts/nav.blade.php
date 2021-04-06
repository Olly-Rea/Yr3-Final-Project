@extends('layouts.app')

@section('nav')
<nav>
    <div id="nav-left">
        <svg id="site-logo">
            <use xlink:href="{{ asset('images/logo.svg#icon') }}"></use>
        </svg>
        {{-- <img src="{{ asset('images/logo.png') }}" alt=""> --}}
        <div id="site-links">
            <a href="@if(Request::is('/'))#@else(){{ route('feed') }}@endif"><b>Home</b></a>
            @auth
            <a href="{{ route('me') }}"><b>My Profile</b></a>
            @else
            <a href="{{ route('register') }}"><b>Sign Up!</b></a>
            @endauth
            <a href="#"><b>AI Chef</b><p class="beta">beta</p></a>
        </div>
    </div>
    <form id="search-bar-container" action="">
        <svg id="search-icon">
            <use xlink:href="{{ asset('images/search.svg#icon') }}"></use>
        </svg>
        <input id="search-bar" type="text" name="search" placeholder="I'm looking for..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'I\'m looking for...'" />
    </form>
</nav>
@endsection
