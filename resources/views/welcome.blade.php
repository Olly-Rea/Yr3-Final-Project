@extends('layouts.app')

@section("styles")
<link href="{{ asset('css/welcome_page.css') }}" rel="stylesheet">
@endsection

@section('title')
<title>{{ config('app.name', 'Laravel') }}</title>
@endsection

@section('nav')
<nav>
    <svg id="site-logo">
        <use xlink:href="{{ asset('images/logo.svg#icon') }}"></use>
    </svg>
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
