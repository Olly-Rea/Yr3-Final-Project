
@foreach ($results as $result)
    @if(gettype($result) == Ingredient::class)
    <a href="{{ route('ingredient', $result->id) }}" class="results-panel">
        <h1>{{ $result->name }}</h1>
    </a>
    @else
    <a href="{{ route('recipe', $result->id) }}" class="results-panel">
        <h1>{{ $result->name }}</h1>
    </a>
    @endif
@endforeach
