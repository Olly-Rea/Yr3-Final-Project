@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
@endsection

@if(Request::is('Me'))
@section('jquery')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection
@section('scripts')
<script src="{{ asset('js/forms/shared.js') }}"></script>
<script src="{{ asset('js/forms/sliders.js') }}"></script>
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
    @forelse ($user->recipes as $recipe)
    <a href="{{ route('recipe', $recipe->id) }}" class="recipe-panel">
        <h2>{{ $recipe->name }}</h2>
        <p><b>Serves: </b>{{ $recipe->serves }}</p>
    </a>
    @empty
    <p>@if(Request::is('Me'))You haven't @else{{ $user->profile->first_name }} hasn't @endif()made any recipes yet!</p>
    @endforelse
</div>
<h2>@if(Request::is('Me'))My @else{{ $user->profile->first_name }}'s @endif()latest ratings</h2>
<div id="user-ratings">
    {{-- Show the 5 most recent --}}
    @forelse ($user->ratings->take(5) as $rating)
    <a href="{{ route('recipe', $rating->recipe->id) }}" class="rating">
        <div class="section">
            <div class="out-of-five">
                <svg>
                    <use xlink:href="{{ asset('images/graphics/star.svg#icon') }}"></use>
                </svg>
                <h3>{{ $rating->out_of_five }}</h3>
            </div>
        </div>
        <div class="section">
            <h4>{{ date("j F Y H:m", strtotime($rating->created_at)) }}</h4>
            <h1>{{ $rating->recipe->name }}</h1>
            <div>
                <div class="spice-wheel">
                    <h3>{{ $rating->spice_value }}</h3>
                </div>
                <div class="sweet-wheel">
                    <h3>{{ $rating->sweet_value }}</h3>
                </div>
                <div class="sour-wheel">
                    <h3>{{ $rating->sour_value }}</h3>
                </div>
                <div class="time-wheel">
                    <h3>{{ $rating->time_taken }}</h3><h4 class="mins">mins</h4>
                </div>
                <div class="difficulty-wheel">
                    <h3>{{ $rating->difficulty_value }}</h3>
                </div>
            </div>
        </div>
    </a>
    @empty
    <p>@if(Request::is('Me'))You haven't @else{{ $user->profile->first_name }} hasn't @endif()made any ratings yet!</p>
    @endforelse
</div>

{{-- Show User 'fridge' (if active User's profile) --}}
@if(Request::is('Me'))
<h2>My Preferences</h2>
<p>Feel free to update your preferences here</p>
<div id="user-prefs">
    <div id="sliders">
        <label for="spice_val">Spice</label>
        <p>Show me recipes with a spice rating up to this value:</p>
        <div id="spice-slider" class="slider-container">
            <input type="number" name="spice_val" value="{{ $user->profile->spice_pref }}" hidden>
            <div id="spice-slider-range" class="slider-range"></div>
        </div>
        <label for="sweet_val">Sweet</label>
        <p>Show me recipes with a 'sweetness' rating up to this value:</p>
        <div id="sweet-slider" class="slider-container">
            <input type="number" name="sweet_val" value="{{ $user->profile->sweet_pref }}" hidden>
            <div id="sweet-slider-range" class="slider-range"></div>
        </div>
        <label for="sour_val">Sour</label>
        <p>Show me recipes with a sour rating up to this value:</p>
        <div id="sour-slider" class="slider-container">
            <input type="number" name="sour_val" value="{{ $user->profile->sour_pref }}" hidden>
            <div id="sour-slider-range" class="slider-range"></div>
        </div>
        <label for="diff_val">Difficulty</label>
        <p>Show me recipes with difficulty ratings up to this value:</p>
        <div id="diff-slider" class="slider-container">
            <input type="number" name="diff_val" value="{{ $user->profile->diff_pref }}" hidden>
            <div id="diff-slider-range" class="slider-range"></div>
        </div>
    </div>
</div>


<h2>My Fridge</h2>
<div id="user-fridge">
    <div id="ingredient-search" class="search-bar">
        <input id="ingredient-search" type="text" name="search" placeholder="Start typing to add more!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to add more!'"/>
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
        <input type="text" name="search" placeholder="Start typing to add more!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to add more!'"/>
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
