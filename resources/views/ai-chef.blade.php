@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/ai_page.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('scripts-app')
<script src="{{ asset('js/forms/shared.js') }}"></script>
<script src="{{ asset('js/forms/ingredientSearch.js') }}"></script>
@endsection

@section('content')
<h1>Please Note!</h1>
<h3>Sadly, due to technical limitations, the TensorFlow model could not be deployed here for this demo.</h3>
<h3>Instead, please enjoy some generations we made earlier!</h3>
<div id="recipe-page" @if(is_null($recipe))style="display:none"@endif>
    <h2>Ingredients:</h2>
    <div id="ingredients-container">
    @if(!is_null($recipe))
        @foreach($recipe->ingredients as $ingredient)
            <div class="ingredient-panel">
                {{-- <h3 class="amount">@if($ingredient->pivot->measure != ""){{ $ingredient->pivot->amount }} {{ $ingredient->pivot->measure }}@else{{ $ingredient->pivot->amount }}@endif</h3> --}}
                <a class="name">{{ $ingredient->name }}</a>
                @if(count($ingredient->alternatives))<p>Alternatives!</p>@endif
            </div>
        @endforeach
    @endif
    </div>
    <h2>Instructions:</h2>
    <div id="directions-container">
    @if(!is_null($recipe))
        @foreach($recipe->instructions as $key => $instruction)
        <div class="directions-panel">
            <h3 class="step">{{ $key }}</h3>
            <p class="content">{{ $instruction->content }}</p>
        </div>
        @endforeach
    @endif
    </div>
    {{-- <div id="rating-panel">
        <div id="spice-rating">
            <input type="number" name="spice_val">
        </div>
        <div id="sweet-rating">
            <input type="number" name="spice_val">
        </div>
        <div id="sour-rating">
            <input type="number" name="spice_val">
        </div>
        <div id="diff-rating">
            <input type="number" name="spice_val">
        </div>
    </div> --}}
</div>
<div id="fridge-page" @if(!is_null($recipe))style="display: none"@endif>
    <h2>You need at least 5 ingredients in your firdge to use the AI chef!</h2>
    <div id="fridge-form">
        <div id="ingredient-search" class="search-bar">
            <div id="results-container" style="display: none"></div>
            <input id="ingredient-search" type="text" name="search" placeholder="Add some here!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to see results!'"/>
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
</div>
@endsection
