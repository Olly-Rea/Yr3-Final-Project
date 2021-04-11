
@forelse ($results as $result)
    @if(get_class($result) == 'App\Models\Ingredient')
    <a href="{{ route('ingredient', $result->id) }}" class="results-panel">
        <h1>{{ $result->name }}</h1>
        <p class="label">Ingredient</p>
    </a>
    @else
    <a href="{{ route('recipe', $result->id) }}" class="results-panel">
        <h1>{{ $result->name }}</h1>
        <p class="label">Recipe</p>
    </a>
    @endif
@empty
<p>No results could be found for your search!</p>
@endforelse
