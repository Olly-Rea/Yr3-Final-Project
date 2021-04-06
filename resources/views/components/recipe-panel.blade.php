@foreach($recipes as $recipe)
<div class="recipe-panel">
    <div class="recipe-title-panel">
        <div class="recipe-title">
            <a href="{{ route('profile', $recipe->user->id) }}">{{ $recipe->user->first_name }} {{ $recipe->user->last_name }} • <i>{{ date("j F Y", strtotime($recipe->created_at)) }}</i></a>
            <a href="{{ route('recipe', $recipe->id) }}"><h1><b>{{ $recipe->name }}</b></h1></a>
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
                @if($ingredient->pivot->specifier == "s")
                <li><a href={{ $alternative->references[0] }} target="blank_">{{ $alternative->pivot->amount }} {{ $alternative->pivot->measure }} - {{ $alternative->name }}{{ $alternative->pivot->specifier }}</a></li>
                @else
                <li><a href={{ $alternative->references[0] }} target="blank_">{{ $alternative->pivot->amount }} {{ $alternative->pivot->measure }} - {{ $alternative->pivot->specifier }} {{ $alternative->name }}</a></li>
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
</div>
@endforeach
