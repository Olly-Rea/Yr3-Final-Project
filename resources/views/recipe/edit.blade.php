@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/recipe_edit.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{ asset('js/forms/shared.js') }}"></script>
<script src="{{ asset('js/forms/sliders.js') }}"></script>
<script src="{{ asset('js/recipe/edit.js') }}"></script>
@endsection

@section('title')Recipe App - {{ isset($recipe) ? $recipe->name : "Untitled" }}@endsection

@section('content')
<form action="{{ route('recipe.save') }}" method="POST" id="recipe-form">
    @csrf

    @if (isset($recipe))
    <input type="number" name="recipe_id" value="{{ $recipe->id }}" hidden>
    @endif

    <div id="recipe-title">
        <input type="text" name="name" value="{{ isset($recipe) ? $recipe->name : "Untitled" }}" required>
        @if ($errors->has('name'))<p class="form-error-msg">{{ $errors->first('name') }}</p> @endif
        <div id="serves">
            <p><b>Serves: </b></p>
            <input type="number" name="serves" maxlength="3" value="{{ isset($recipe) ? $recipe->serves : "1" }}">
        </div>
        @if ($errors->has('serves'))<p class="form-error-msg">{{ $errors->first('serves') }}</p> @endif
    </div>

    <h2>Ingredients:</h2>
    <div id="ingredients">
        <div id="ingredient-search" class="search-bar">
            <input type="text" name="search" placeholder="Start typing to add more!" onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing to add more!'"/>
        </div>
        <div id="ingredients-select" class="item-container">
            <p class="initial-msg">Use the search bar to start adding ingredients to the recipe!</p>
        </div>
        @if(isset($recipe))
            @foreach($recipe->ingredients as $index => $ingredient)
            {{-- <h3 class="name">{{ $ingredient->name }}@if($ingredient->pivot->misc_info != "") <span>({{ $ingredient->pivot->misc_info }})</span>@endif</h3> --}}
            <div class="ingredient-panel">
                <input type="number" name="ingredient[{{ $index }}][id]" value="{{ $ingredient->id }}" hidden readonly>
                <input type="text" name="ingredient[{{ $index }}][name]" value="{{ $ingredient->name }}" hidden readonly>
                <div class="amount">
                    <input type="number" name="ingredient[{{ $index }}][amount]" maxlength="4" value="{{ $ingredient->pivot->amount }}">
                    <input type="text" name="ingredient[{{ $index }}][measure]" maxlength="10" value="{{ $ingredient->pivot->measure }}">
                </div>
                <h3 class="name">{{ $ingredient->name }}</h3>
                <div class="remove">
                    <svg>
                        <use xlink:href="{{ asset('images/graphics/remove.svg#icon') }}"></use>
                    </svg>
                </div>
            </div>
            @endforeach
        @else
            @if(old('ingredient') != null)
                @foreach(old('ingredient') as $index => $ingredient)
                <div class="ingredient-panel">
                    <input type="number" name="ingredient[{{ $index }}][id]" value="{{ $ingredient['id'] }}" hidden readonly>
                    <input type="text" name="ingredient[{{ $index }}][name]" value="{{ $ingredient['name'] }}" hidden readonly>
                    <div class="amount">
                        <input type="number" name="ingredient[{{ $index }}][amount]" maxlength="4" value="{{ $ingredient['amount'] }}">
                        <input type="text" name="ingredient[{{ $index }}][measure]" maxlength="10" value="{{ $ingredient['measure'] }}">
                    </div>
                    <h3 class="name">{{ $ingredient['name'] }}</h3>
                    <div class="remove">
                        <svg>
                            <use xlink:href="{{ asset('images/graphics/remove.svg#icon') }}"></use>
                        </svg>
                    </div>
                </div>
                @endforeach
            @endif
        @endif
    </div>
    @if ($errors->has('ingredient'))<p class="form-error-msg">{{ $errors->first('ingredient') }}</p> @endif

    <h2>Directions:</h2>
    <div id="directions">
        @if(isset($recipe))
            @foreach($recipe->instructions as $index => $instruction)
            <div class="direction-panel">
                <h3 class="step">{{ $index+1 }}</h3>
                <input type="text" name="instruction[{{ $index }}]" class="content" value="{{ $instruction->content }}" placeholder="Start typing your direction here..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing your direction here...'">
                <div class="remove">
                    <svg>
                        <use xlink:href="{{ asset('images/graphics/remove.svg#icon') }}"></use>
                    </svg>
                </div>
            </div>
            @endforeach
        @else
            @if(old('instruction') != null)
                @foreach(old('instruction') as $index => $instruction)
                <div class="direction-panel">
                    <h3 class="step">{{ $index + 1 }}</h3>
                    <input type="text" name="instruction[{{ $index }}]" class="content" value="{{ $instruction->content }}" placeholder="Start typing your direction here..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing your direction here...'">
                    <div class="remove">
                        <svg>
                            <use xlink:href="{{ asset('images/graphics/remove.svg#icon') }}"></use>
                        </svg>
                    </div>
                </div>
                @endforeach
            @endif
        @endif
        <p class="placeholder" @if(isset($recipe) && count($recipe->instructions))style="display: none"@endif>Press "Add" to start adding directions!</p>
        <h3 id="add-direction">Add</h3>
    </div>
    @if ($errors->has('direction'))<p class="form-error-msg">{{ $errors->first('direction') }}</p> @endif

</form>
<h3 id="save">Save</h3>
@endsection

@section('site-overlay')
<div id="alert" class="prompt hidden" style="display: none">
    <h1>Just a sec!</h1>
    <p class="message"></p>
    <button>Okay!</button>
</div>

@endsection
