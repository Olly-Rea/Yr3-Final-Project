@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/recipe_page.css') }}" rel="stylesheet">
@endsection

@section('title')
<title>{{ config('app.name', 'Laravel') }}</title>
@endsection

@section('content')
{{-- Top bar for all recipe quick-info --}}
<div class="recipe-title-panel">
    <a href="{{ route('profile', $recipe->user->id) }}" class="author-info">
        <div class="profile-image-container">

        </div>
        {{ $recipe->user->first_name }} {{ $recipe->user->last_name }} • <i>{{ date("j F Y", strtotime($recipe->created_at)) }}</i>
    </a>
    <div class="recipe-title">
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
        <div class="difficulty-info">
            <div class="difficulty-wheel info-wheel">
                <h4>{{ rand(1, 10) }}</h4>
            </div>
            <p>Difficulty</p>
        </div>
    </div>
</div>

<div class="ingredients-panel">
    <h2>Ingredients:</h2>
    @foreach($recipe->ingredients as $ingredient)
        @if ($ingredient->pivot->misc_info != "")
        <a href="{{ route('ingredient', $ingredient->id) }}"><li>{{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measure }} - {{ $ingredient->name }} ({{ $ingredient->pivot->misc_info }})</li></a>
        @else
        <a href="{{ route('ingredient', $ingredient->id) }}"><li>{{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measure }} - {{ $ingredient->name }}</li></a>
        @endif
        @if(count($ingredient->alternatives) > 0)
        <ul>
        @endif
        @foreach($ingredient->alternatives as $alternative)
            @if ($alternative->pivot->misc_info != "")
            <a href="{{ route('ingredient', $alternative->id) }}"><li>{{ $alternative->pivot->amount }} {{ $alternative->pivot->measure }} - {{ $alternative->name }} ({{ $alternative->pivot->misc_info }})</li></a>
            @else
            <a href="{{ route('ingredient', $alternative->id) }}"><li>{{ $alternative->pivot->amount }} {{ $alternative->pivot->measure }} - {{ $alternative->name }}</li></a>
            @endif
        @endforeach
        @if(count($ingredient->alternatives) > 0)
        </ul>
        @endif
        <br>
    @endforeach
    </ul>
    <h2>Instructions:</h2>
    @foreach($recipe->instructions as $instruction)
    <p>{{ $instruction->content }}</p>
    @endforeach
</div>
@endsection
