@forelse ($results as $result)
<div class="item unselected" {{-- style="display:none" --}}>
    <input type="hidden" name="@if(get_class($result) == 'App\Models\Ingredient')ingredient_id[]@else()allergen_id[]@endif" value="{{ $result->id }}">
    <h3>{{ $result->name }}</h3>
</div>
@empty
<p class="nothing">Nothing matches that search!</p>
@endforelse
