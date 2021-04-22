@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('content')

<div class="profile-image-container">
    <div class="profile-image">
        <img src="{{ $user->profileImage() }}" alt="{{ $user->profile->first_name }} {{ $user->profile->last_name }}">
    </div>
</div>

<h1>{{ $user->profile->first_name }} {{ $user->profile->last_name }}</h1>
<div id="about-user">
    <p>About User - coming soon</p>
</div>

<h2>@if(Request::is('Me'))My @else{{ $user->profile->first_name }}'s @endif()Public Recipes</h2>
<div id="user-recipes">
@foreach ($user->recipes as $recipe)
    <a href="{{ route('recipe', $recipe->id) }}" class="recipe-panel">
        <h2>{{ $recipe->name }}</h2>
        <p><b>Serves: </b>{{ $recipe->serves }}</p>
    </a>
@endforeach
</div>

<h2>@if(Request::is('Me'))My @else{{ $user->profile->first_name }}'s @endif()ratings</h2>
<div id="user-ratings">
@foreach ($user->ratings as $rating)

@endforeach
</div>

{{-- Show User 'fridge' (if active User's profile) --}}
@if(Request::is('Me'))
<h2>My Fridge</h2>
<div id="user-fridge">
    @foreach ($user->fridge->ingredients as $ingredient)
    <div class="fridge-ingredient">
        <h3 class="amount">@if($ingredient->pivot->measure != ""){{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measure }}@else{{ $inredient->pivot->amount }}@endif</h3>
        <a href="{{ route('ingredient', $ingredient->id) }}" class="name">{{ $ingredient->name }}</a>
    </div>
    @endforeach
    <form action="">
        <button type="button">Add Ingredient</button>
    </form>
</div>
@endif

@if (Request::is('Me'))
<button onclick="window.location.href='{{ route('logout') }}'; document.getElementById('logout-form').submit();">Logout</button>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" hidden>
    @csrf
</form>
@endif

@endsection

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-jet-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout> --}}
