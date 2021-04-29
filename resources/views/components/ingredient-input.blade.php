<div class="ingredient-panel">
    <input type="number" name="ingredient[{{ $index }}][id]" value="{{ $ingredient->id }}" hidden>
    <div class="amount">
        <input type="number" name="ingredient[{{ $index }}][amount]" maxlength="4" value="0">
        <input type="text" name="ingredient[{{ $index }}][measure]" maxlength="10" value="g">
    </div>
    <h3 class="name">{{ $ingredient->name }}</h3>
    <div class="remove">
        <svg>
            <use xlink:href="{{ asset('images/graphics/remove.svg#icon') }}"></use>
        </svg>
    </div>
</div>
