@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/ingredient_page.css') }}" rel="stylesheet">
@endsection

@section('title')Recipe App - {{ $ingredient->name }}@endsection


@section('content')

<h1>{{ $ingredient->name }}</h1>

<h2>Ingredient Stats:</h2>
<div id="stats-container">
    <div>
        <div class="info-graphic">
            <h2>Energy</h2>
            <p>(kcal)</p>
        </div>
        <p>@if($ingredient->energy_kcal_100g > 0){{ $ingredient->energy_kcal_100g }}@else()<i>No data</i>@endif</p>
    </div>
    <div>
        <div class="info-graphic">
            <h2>Carbs</h2>
            <p>(Carbohydrates)</p>
        </div>
        <p>@if($ingredient->carbohydrates_100g > 0){{ $ingredient->carbohydrates_100g }}g @else()<i>No data</i>@endif</p>
    </div>
    <div>
        <div class="info-graphic">
            <h2>Sugars</h2>
        </div>
        <p>@if($ingredient->sugars_100g > 0){{ $ingredient->sugars_100g }}g @else()<i>No data</i>@endif</p>
    </div>
    <div>
        <div class="info-graphic">
            <h2>Protein</h2>
        </div>
        <p>@if($ingredient->proteins_100g > 0){{ $ingredient->proteins_100g }}g @else()<i>No data</i>@endif</p>
    </div>
    <div>
        <div class="info-graphic">
            <h2>Fiber</h2>
        </div>
        <p>@if($ingredient->fiber_100g > 0){{ $ingredient->fiber_100g }}g @else()<i>No data</i>@endif</p>
    </div>
    <div>
        <div class="info-graphic">
            <h2>Salt</h2>
        </div>
        <p>@if($ingredient->salt_100g > 0){{ $ingredient->salt_100g }}g @else()<i>No data</i>@endif</p>
    </div>
    <div id="small-print-info">
        <p>*per 100g</p>
        <p>**based on open-source data from <a href="https://world.openfoodfacts.org/" target="_blank">world.openfoodfacts.org</a></p>
    </div>
</div>

<h2>Known Allergens:</h2>
<div id="allergen-container">
    @forelse ($ingredient->allergens as $allergen)
    <div class="info-panel">
        <p>{{ $allergen->name }}</p>
    </div>
    @empty
    <p>None</p>
    @endforelse
</div>

<h2>Known Traces:</h2>
<div id="trace-container">
    @forelse ($ingredient->traces as $trace)
    <div class="info-panel">
        <p>{{ $trace->name }}</p>
    </div>
    @empty
    <p>None</p>
    @endforelse
</div>


<h2>Categories:</h2>
<div id="category-container">
    @forelse ($ingredient->categories as $category)
    <div class="info-panel">
        <p>{{ $category->name }}</p>
    </div>
    @empty
    <p>None</p>
    @endforelse
</div>

<h2>Labels:</h2>
<div id="label-container">
    @forelse ($ingredient->labels as $label)
    <div class="info-panel">
        <p>{{ $label->name }}</p>
    </div>
    @empty
    <p>None</p>
    @endforelse
</div>

<h2>References:</h2>
<p>All data collated from / based on the following <a href="https://world.openfoodfacts.org/" target="_blank">world.openfoodfacts.org</a> links</p>
<div id="references-container">
    @if(is_countable($ingredient->references))
    @forelse ($ingredient->references as $reference)
    <a href="{{ $reference }}" target="_blank">{{ $reference }}</a>
    @empty
    <p>None</p>
    @endforelse
    @else
    <a href="{{ $ingredient->references }}" target="_blank">{{ $ingredient->references }}</a>
    @endif
</div>

@endsection
