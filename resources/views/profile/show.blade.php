@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
@endsection

@if(Request::is('Me'))
@section('scripts-app')
<script src="{{ asset('js/forms/shared.js') }}"></script>
<script src="{{ asset('js/forms/ingredientSearch.js') }}"></script>
<script src="{{ asset('js/forms/allergenSearch.js') }}"></script>
<script src="{{ asset('js/forms/profileImage.js') }}"></script>
@endsection
@endif

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('content')
@if(Request::is('Me'))
<div id="profile-form">
    <div class="profile-image-container">
        <div class="profile-image">
            <img src="{{ $user->profileImage() }}" alt="{{ $user->profile->first_name }} {{ $user->profile->last_name }}">
        </div>
        <div class="edit-overlay">
            <label class="menu-item">
                <input type="file" name="profile_image" accept=".jpg, .jpeg, .png, .bmp" hidden>
                <span>
                    <svg>
                        <use xlink:href="{{ asset('images/graphics/pen.svg#icon') }}"></use>
                    </svg>
                </span>
            </label>
        </div>
    </div>
</div>

@else
<div class="profile-image-container">
    <div class="profile-image">
        <img src="{{ $user->profileImage() }}" alt="{{ $user->profile->first_name }} {{ $user->profile->last_name }}">
    </div>
</div>
@endif
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
    @forelse ($user->ratings as $rating)
    <div>
        <h4>{{ $rating->created_at }}</h4>
        <p>{{ $rating->recipe->name }}</p>
        <p>{{ $rating->spice_value }}, {{ $rating->sweet_value }}, {{ $rating->sour_value }}, {{ $rating->difficulty_value }}</p>
    </div>
    @empty
    <p>@if(Request::is('Me'))You haven't @else{{ $user->profile->first_name }} hasn't @endif()made any ratings yet</p>
    @endforelse
</div>

{{-- Show User 'fridge' (if active User's profile) --}}
@if(Request::is('Me'))
<h2>My Fridge</h2>
<div id="user-fridge">
    <div id="ingredient-search" class="search-bar">
        <div id="results-container" style="display: none"></div>
        <input id="ingredient-search" type="text" name="search" placeholder="Start typing to see results!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to see results!'"/>
    </div>
    <div id="fridge-ingredients" class="item-container">
        @foreach ($user->fridge->ingredients as $ingredient)
        <div class="item selected">
            <input type="hidden" name="fridge[]" value="{{ $ingredient->id }}">
            <h3>{{ $ingredient->name }}</h3>
        </div>
        @endforeach
        <p class="initial-msg" @if(count($user->fridge->ingredients))style="display: none"@endif>Use the search bar to start adding any ingredients you have!</p>
    </div>
</div>

<h2>My Allergens</h2>
<p>You are in no way obligated to disclose this information, but we can quickly filter out recipes containing these allergens if indicated below</p>
<div id="user-allergens">
    <div id="allergen-search" class="search-bar">
        <div id="results-container" style="display: none"></div>
        <input type="text" name="search" placeholder="Start typing to see results!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to see results!'"/>
    </div>
    <div id="profile-allergens" class="item-container">
        @foreach($user->profile->allergens as $allergen)
        <div class="item selected">
            <input type="hidden" name="allergens[]" value="{{ $allergen->id }}">
            <h3>{{ $allergen->name }}</h3>
        </div>
        @endforeach
        <p class="initial-msg" @if(count($user->profile->allergens))style="display: none"@endif>You haven't indicated any allergens yet!<br><b>All recipes will be shown</b></p>
    </div>
</div>
@endif

@if (Request::is('Me'))
<h3 id="logout-link">Logout</h3>
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
