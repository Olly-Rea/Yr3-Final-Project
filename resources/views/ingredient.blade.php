@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/ingredient_page.css') }}" rel="stylesheet">
@endsection

@section('title')
<title>{{ config('app.name', 'Laravel') }}</title>
@endsection

@section('content')

<h1>{{ $ingredient->name }}</h1>

<div>
    <h2>Ingredient Stats:</h2>
    <p>energy (kcal) per 100g: <b>{{ $ingredient->energy_kcal_100g }}g</b></p>
    <p>carbohydrates per 100g: <b>{{ $ingredient->carbohydrates_100g }}g</b></p>
    <p>sugars per 100g: <b>{{ $ingredient->sugars_100g }}g</b></p>
    <p>proteins per 100g: <b>{{ $ingredient->proteins_100g }}g</b></p>
    <p>fiber per 100g: <b>{{ $ingredient->fiber_100g }}g</b></p>
    <p>salt per 100g: <b>{{ $ingredient->salt_100g }}g</b></p>
</div>

<div>
    <h2>Categories:</h2>
    @forelse ($ingredient->categories as $category)
    <p>{{ $category->name }}</p>
    @empty
    <p>None</p>
    @endforelse
</div>

<div>
    <h2>Known Allergens:</h2>
    @forelse ($ingredient->allergens as $allergen)
    <p>{{ $allergen->name }}</p>
    @empty
    <p>None</p>
    @endforelse
</div>

<div>
    <h2>Known Traces:</h2>
    @forelse ($ingredient->traces as $trace)
    <p>{{ $trace->name }}</p>
    @empty
    <p>None</p>
    @endforelse
</div>

<div>
    <h2>Labels:</h2>
    @forelse ($ingredient->labels as $label)
    <p>{{ $label->name }}</p>
    @empty
    <p>None</p>
    @endforelse
</div>

@endsection
