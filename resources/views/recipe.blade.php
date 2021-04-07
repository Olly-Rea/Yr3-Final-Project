@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/recipe_page.css') }}" rel="stylesheet">
@endsection

@section('title')Recipe App - {{ $recipe->name }}@endsection

@section('content')
{{-- Top bar for all recipe quick-info --}}

<div id="recipe-title">
    <h1><b>{{ $recipe->name }}</b></h1>
    <p><b>Serves: </b>{{ $recipe->serves }}</p>
</div>

<div class="recipe-title-panel">
    <a href="{{ route('profile', $recipe->user->id) }}" class="author-info">
        <div class="profile-image-container">
            <div class="profile-image">
                <img src="{{ $recipe->user->profileImage() }}" alt="{{ $recipe->user->first_name }} {{ $recipe->user->last_name }}">
            </div>
        </div>
        <h3>{{ $recipe->user->first_name }} {{ $recipe->user->last_name }} • <i>{{ date("j F Y", strtotime($recipe->created_at)) }}</i></h3>
    </a>
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
        <div class="difficulty-info">
            <div class="difficulty-wheel info-wheel">
                <h4>{{ rand(1, 10) }}</h4>
            </div>
            <p>Difficulty</p>
        </div>
    </div>
</div>

<div id="ingredients-panel">
    <h2>Ingredients:</h2>
    @foreach($ingredients as $ingredient)
        <a href="{{ route('ingredient', $ingredient->id) }}">@if($ingredient->pivot->measure != ""){{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measure }} - @else{{ $ingredient->pivot->amount }}@endif {{ $ingredient->name }}@if($ingredient->pivot->misc_info != "") ({{ $ingredient->pivot->misc_info }})@endif</a>
        @forelse($ingredient->alternatives as $alternative)
            <div class="alternative-container">
                <a href="{{ route('ingredient', $alternative->id) }}">@if($alternative->pivot->measure != ""){{ $alternative->pivot->amount }} {{ $alternative->pivot->measure }} - @else{{ $alternative->pivot->amount }}@endif {{ $alternative->name }}@if($alternative->pivot->misc_info != "") ({{ $alternative->pivot->misc_info }})@endif</a>
            </div>
        @empty
        @endforelse
    @endforeach
</div>
<div id="instructions-panel">
    <h2>Instructions:</h2>
    @foreach($recipe->instructions as $instruction)
    <p>{{ $instruction->content }}</p>
    @endforeach
</div>
@endsection
