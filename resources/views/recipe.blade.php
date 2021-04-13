@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/recipe_page.css') }}" rel="stylesheet">
@endsection

@section('title')Recipe App - {{ $recipe->name }}@endsection

@section('content')
{{-- Top bar for all recipe quick-info --}}

<div id="recipe-title-container">
    <div id="recipe-title">
        <h1><b>{{ $recipe->name }}</b></h1>
        <p><b>Serves: </b>{{ $recipe->serves }}</p>
    </div>
    <a href="{{ route('profile', $recipe->user->id) }}" id="author-info">
        <div class="profile-image-container">
            <div class="profile-image">
                <img src="{{ $recipe->user->profileImage() }}" alt="{{ $recipe->user->first_name }} {{ $recipe->user->last_name }}">
            </div>
        </div>
        <div id="name-date-info">
            <h2>{{ $recipe->user->profile->first_name }} {{ $recipe->user->profile->last_name }}</h2>
            <h4><i>{{ date("j F Y", strtotime($recipe->created_at)) }}</i></h4>
        </div>
    </a>
</div>

<div id="quick-info-container">
    <h2>Based on User reviews:</h2>
    <div>
        @php
            $totalRatings = $recipe->ratings->count() > 0 ? $recipe->ratings->count() : 1;
        @endphp
        <div class="spice-info">
            <div class="spice-wheel info-wheel">
                <h4>{{ round(($recipe->ratings->sum('spice_value')/$totalRatings)*10) }}</h4>
            </div>
            <p>Spice</p>
        </div>
        <div class="sweet-info">
            <div class="sweet-wheel info-wheel">
                <h4>{{ round(($recipe->ratings->sum('sweet_value')/$totalRatings)*10) }}</h4>
            </div>
            <p>Sweetness</p>
        </div>
        <div class="sour-info">
            <div class="sour-wheel info-wheel">
                <h4>{{ round(($recipe->ratings->sum('sour_value')/$totalRatings)*10) }}</h4>
            </div>
            <p>Sourness</p>
        </div>
        <div class="time-info">
            <div class="time-wheel info-wheel">
                <h4>{{ ceil($recipe->ratings->sum('time_taken')/$totalRatings) }}</h4><h4 class="mins">mins</h4>
            </div>
            <p>Prep time (avg)</p>
        </div>
        <div class="difficulty-info">
            <div class="difficulty-wheel info-wheel">
                <h4>{{ round($recipe->ratings->sum('difficulty_value')/$totalRatings, 1) }}</h4>
            </div>
            <p>Difficulty</p>
        </div>
    </div>
</div>

<h2>Ingredients:</h2>
<div id="ingredients-container">
    @foreach($ingredients as $ingredient)
        <div class="ingredient-panel">
            <h3 class="amount">@if($ingredient->pivot->measure != ""){{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measure }}@else{{ $ingredient->pivot->amount }}@endif</h3>
            {{-- <h3 class="name">{{ $ingredient->name }}@if($ingredient->pivot->misc_info != "") <span>({{ $ingredient->pivot->misc_info }})</span>@endif</h3> --}}
            <a href="{{ route('ingredient', $ingredient->id) }}" class="name">{{ $ingredient->name }}@if($ingredient->pivot->misc_info != "") <span>({{ $ingredient->pivot->misc_info }})</span>@endif</a>

            @if(count($ingredient->alternatives))<p>Alternatives!</p>@endif
        </div>
        @forelse($ingredient->alternatives as $alternative)
            <div class="alternative-container">
                <a href="{{ route('ingredient', $alternative->id) }}" class="alternative-panel">
                    <h3 class="amount">@if($alternative->pivot->measure != ""){{ $alternative->pivot->amount }} {{ $alternative->pivot->measure }}@else{{ $alternative->pivot->amount }}@endif</h3>
                    <h3 class="name">{{ $alternative->name }}@if($alternative->pivot->misc_info != "") <span>({{ $alternative->pivot->misc_info }})</span>@endif</h3>
                </a>
            </div>
        @empty
        @endforelse
    @endforeach
</div>

<h2>Instructions:</h2>
<div id="directions-container">
    @foreach($recipe->instructions as $key => $instruction)
    <div class="directions-panel">
        <h3 class="step">{{ $key }}</h3>
        <p class="content">{{ $instruction->content }}</p>
    </div>
    @endforeach
</div>
@endsection

{{-- Site overlay for review form --}}
@section('site-overlay')
<div id="ratings-form">
    <h1>Please take a couple seconds to review this recipe!</h1>
    <form action="">

    </form>
    <p>Not now</p>
</div>
@endsection
