@extends('layouts.nav')

@section("styles")
<link href="{{ secure_asset('css/feed_page.css') }}" rel="stylesheet">
@endsection
@section('scripts')
<script src="{{ secure_asset('js/recipe/feed.js') }}"></script>
@endsection

@section('title')Ideas Board @endsection

@section('content')

@if(count($recipes))
<h2>Some suggestions for you!</h2>
@auth<h3>- The following recipes have been selected specifically based on your indicated preferences -</h3>@endauth
@endif
@forelse($recipes as $recipe)
<div class="recipe-panel">
    <div class="recipe-title-panel">
        <div class="star-rating">
            <svg>
                <use xlink:href="{{ secure_asset('images/graphics/star.svg#icon') }}"></use>
            </svg>
            <h3>{{ round($recipe->ratings->avg('out_of_five'), 1) }}</h3>
        </div>
        <div class="recipe-title">
            <a href="{{ route('profile', $recipe->user->id) }}">{{ $recipe->user->profile->first_name }}@if(isset($recipe->user->profile->last_name)) {{ $recipe->user->profile->last_name }}@endif • <i>{{ date("j F Y", strtotime($recipe->created_at)) }}</i></a>
            <a href="{{ route('recipe', $recipe->id) }}"><h1><b>{{ $recipe->name }}</b></h1></a>
            <p><b>Serves:</b> {{ $recipe->serves }}</p>
        </div>
    </div>
    <div class="quick-info">
        <div class="spice-info">
            <div class="spice-wheel info-wheel">
                <h4>{{ round($recipe->ratings->avg('spice_value')*10) }}</h4>
            </div>
            <p>Spice</p>
        </div>
        <div class="sweet-info">
            <div class="sweet-wheel info-wheel">
                <h4>{{ round($recipe->ratings->avg('sweet_value')*10) }}</h4>
            </div>
            <p>Sweetness</p>
        </div>
        <div class="sour-info">
            <div class="sour-wheel info-wheel">
                <h4>{{ round($recipe->ratings->avg('sour_value')*10) }}</h4>
            </div>
            <p>Sourness</p>
        </div>
        <div class="time-info">
            <div class="time-wheel info-wheel">
                <h4>{{ ceil($recipe->ratings->avg('time_taken')) }}</h4><h4 class="mins">mins</h4>
            </div>
            <p>Prep time (avg)</p>
        </div>
        <div class="difficulty-info">
            <div class="difficulty-wheel info-wheel">
                <h4>{{ round($recipe->ratings->avg('difficulty_value'), 1) }}</h4>
            </div>
            <p>Difficulty</p>
        </div>
    </div>
</div>
@empty
<div class="centered">
    <h2>No recipes could be found!</h2>
    <h3>- Try reloading the page -</h3>
</div>
@endforelse

@if(count($recipes))
<div id="cookbook-refresh">
    <svg>
        <use xlink:href="{{ secure_asset('images/graphics/refresh.svg#icon') }}"></use>
    </svg>
</div>
@endif
@endsection
