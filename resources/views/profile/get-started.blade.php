@extends('layouts.app')

@section("styles")
<link href="{{ asset('css/get_started.css') }}" rel="stylesheet">
@endsection

@section('jquery')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection

@section("scripts-app")
<script src="{{ asset('js/profile/create.js') }}"></script>
@endsection

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('nav')
<div id="nav-left">
    <a href="#" id="site-logo">
        <svg>
            <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
        </svg>
    </a>
</div>
<div id="site-links">
    <div id="progress"></div>
    <h3>Your Details</h3>
    <h3>Allergens</h3>
    <h3>Preferences</h3>
    <h3>'My Fridge'</h3>
</div>
<h3 id="logout-link">Logout</h3>
@endsection

@section('content')
    <div id="greeting-page" class="fullscreen">
        <h1>Welcome to the Recipe App!</h1>
        <p>Before you can get started, please take a moment to fill in a couple details for us</p>
        <button>Get Started</button>
    </div>
    {{-- Profile information form --}}
    <div id="profile-form" class="fullscreen" style="display: none">
        <h1>First up, a little bit about yourself</h1>
        <p>Please feel free to update any of this information as you see fit</p>
        <form action="{{ route('me.update') }}">
            @csrf
            <label for="profile_image">Profile Photo</label>
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
            @if($errors->me->has('profile_image'))<p class="form-error-msg">{{ $errors->me->first('profile_image') }}</p>@endif
            <div class="form-row">
                <div class="form-item">
                    <label for="edit_f_name">First Name:</label>
                    <input type="text" name="edit_f_name" value="{{ old("edit_f_name") ? old("edit_f_name") : $user->profile->first_name }}"
                        onfocus="this.placeholder = ''" onfocusout="this.placeholder = '{{ $user->profile->first_name }}'">
                    @if ($errors->personal->has('edit_f_name')) <p class="form-error-msg">{{ $errors->personal->first('edit_f_name') }}</p> @endif
                </div>
                <div class="form-item">
                    <label for="edit_l_name">Last Name:</label>
                    <input type="text" name="edit_l_name" value="{{ old("edit_l_name") ? old("edit_l_name") : $user->profile->last_name}}"
                        placeholder="Last name" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Last name'">
                    @if ($errors->personal->has('edit_l_name')) <p class="form-error-msg">{{ $errors->personal->first('edit_l_name') }}</p> @endif
                </div>
            </div>
        </form>
        <div class="nav-items">
            <h3 class="next">Next</h3>
        </div>
    </div>
    {{-- Allergen information form --}}
    <div id="allergen-form" class="fullscreen" style="display:none">
        <h1>Secondly, please indicate any allergens you wish to avoid</h1>
        <p>This is vital if you have any allergens you wish to avoid, as we will ensure to prevent recipes being shown to you that contain ingredients that are known to contain these allergens</p>
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
        <div class="nav-items">
            <h3 class="back">Back</h3>
            <h3 class="next">Next</h3>
        </div>
    </div>
    {{-- Food preferences form --}}
    <div id="prefs-form" class="fullscreen" style="display:none">
        <h1>Just a couple more things...</h1>
        <p>Please indicate your food preferences here, this way we can better tailor your experience to recipes we know you'll like!</p>
        <form action="{{ route('me.update') }}">
            @csrf
            <label for="spice_val">Spice</label>
            <p>Show me recipes with a spice rating up to this value:</p>
            <div id="spice-slider" class="slider-container">
                <input type="number" name="spice_val" value="{{ old("spice_val") ? old("spice_val") : 5 }}" hidden>
                <div id="spice-slider-range" class="slider-range"></div>
            </div>
            <label for="sweet_val">Sweet</label>
            <p>Show me recipes with a 'sweetness' rating up to this value:</p>
            <div id="sweet-slider" class="slider-container">
                <input type="number" name="sweet_val" value="{{ old("sweet_val") ? old("sweet_val") : 5 }}" hidden>
                <div id="sweet-slider-range" class="slider-range"></div>
            </div>
            <label for="sour_val">Sour</label>
            <p>Show me recipes with a sour rating up to this value:</p>
            <div id="sour-slider" class="slider-container">
                <input type="number" name="sour_val" value="{{ old("sour_val") ? old("sour_val") : 5 }}" hidden>
                <div id="sour-slider-range" class="slider-range"></div>
            </div>
            <label for="diff_val">Difficulty</label>
            <p>Show me recipes with difficulty ratings up to this value:</p>
            <div id="diff-slider" class="slider-container">
                <input type="number" name="diff_val" value="{{ old("diff_val") ? old("diff_val") : 5 }}" hidden>
                <div id="diff-slider-range" class="slider-range"></div>
            </div>
        </form>
        <div class="nav-items">
            <h3 class="back">Back</h3>
            <h3 class="next">Next</h3>
        </div>
    </div>
    {{-- Fridge ingredients form --}}
    <div id="fridge-form" class="fullscreen" style="display:none">
        <h1>...And lastly!</h1>
        <p>Please indicate the ingredients you currently have available to you, we will use these to help show you recipes that you can cook here and now!</p>
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
            <p class="initial-msg" @if(count($user->profile->allergens))style="display: none"@endif>Use the search bar to start adding any ingredients you have!</p>
        </div>
        <div class="nav-items">
            <h3 class="back">Back</h3>
            <h3 id="complete">Let's Go!</h3>
        </div>
    </div>
@endsection

@section('site-overlay')
<div id="action" class="prompt hidden" style="display: none">
    <h1 class="prompt-title"></h1>
    <p class="message"></p>
    <button id="confirm-button">Okay!</button>
</div>
@endsection
