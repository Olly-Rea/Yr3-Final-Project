@extends('layouts.app')

@section("styles")
<link href="{{ asset('css/global.css') }}" rel="stylesheet">
@endsection

@section('title')
<title>{{ config('app.name', 'Laravel') }}</title>
@endsection

@section('content')
@foreach($recipes as $recipe)
<div class="recipe-panel">
    <div class="recipe-title-panel">
        <div class="recipe-title">
            <p>{{ $recipe->user->first_name }} {{ $recipe->user->last_name }} • <i>{{ date("j F Y", strtotime($recipe->created_at)) }}</i></p>
            <h1><b>{{ $recipe->name }}</b></h1>
            <p><b>Serves:</b> {{ $recipe->serves }}</p>
        </div>
        <div class="quick-info">
            <div class="spice-info">
                <div class="spice-wheel info-wheel">
                    <h4>{{ rand(1, 100) }}</h4>
                </div>
                <p>Spice</p>
            </div>
            <div class="sweet-info">
                <div class="sweet-wheel info-wheel">
                    <h4>{{ rand(1, 100) }}</h4>
                </div>
                <p>Sweetness</p>
            </div>
            <div class="sour-info">
                <div class="sour-wheel info-wheel">
                    <h4>{{ rand(1, 100) }}</h4>
                </div>
                <p>Sourness</p>
            </div>
            <div class="time-info">
                <div class="time-wheel info-wheel">
                    <h4>{{ rand(10, 120) }}</h4><h4 class="mins">mins</h4>
                </div>
                <p>Timeframe</p>
            </div>
            <div class="challenge-info">
                <div class="challenge-wheel info-wheel">
                    <h4>{{ rand(1, 10) }}</h4>
                </div>
                <p>Challenge</p>
            </div>
        </div>
    </div>
    <div class="ingredients-panel">
        <h2>Ingredients:</h2>
    @if($recipe->ingredients[0]->name == 'placeholder')
        <p><i>This recipe hasn't included any ingredients!</i></p>
    @else
    @foreach($recipe->ingredients as $ingredient)
        @if($ingredient->pivot->specifier == "s")
        <a href={{ $ingredient->url }} target="blank_">{{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measurement }} - {{ $ingredient->name }}{{ $ingredient->pivot->specifier }}</a>
        @else
        <a href={{ $ingredient->url }} target="blank_">{{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measurement }} - {{ $ingredient->pivot->specifier }} {{ $ingredient->name }}</a>
        @endif
        <br>
    @endforeach
    @endif
        <h2>Instructions:</h2>
    @foreach($recipe->instructions as $instruction)
        <p>{{ $instruction->content }}</p>
    @endforeach
    </div>
</div>
@endforeach

{{-- <div _id="screen-split"></div>
<nav>
Authentication Link
    <a href="#">
        <div class="nav-link" >
            {{ __('recipe Feed') }}
        </div>
    </a>
    <a href="#">
        <div class="nav-link" >
            {{ __('Search') }}
        </div>
    </a>
    @auth
    <a href="#">
        <div class="nav-link" >
            {{ __('My recipes') }}
        </div>
    </a>
    @else
    <a href="{{ route('register') }}">
        <div class="nav-link" >
            {{ __('Sign Up') }}
        </div>
    </a>
    @endauth
</nav> --}}

{{-- @guest
    <a href="{{ route('login') }}">
        <div class="nav-link" >
            {{ __('Login') }}
        </div>
    </a>
    @if (Route::has('register'))
    <a href="{{ route('register') }}">
        <div class="nav-link" >
            {{ __('Register') }}
        </div>
    </a>
    @endif
    <div _id="or-container">
        <div class="h-sep"></div>
        <p>Or</p>
        <div class="h-sep"></div>
    </div>
    <a href="{{ route('feed') }}">
        <div class="nav-link" >
            {{ __('Browse as Guest') }}
        </div>
    </a>
@else
<a class="nav-link" >
    {{ Auth::user()->name }}
</a>
<div class="nav-link" >
    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementBy_id('logout-form').submit();">
        {{ __('Logout') }}
    </a>
    <form _id="logout-form" action="{{ route('logout') }}" method="recipe" class="d-none" h_idden>
        @csrf
    </form>
</div>
@endguest --}}
@endsection
