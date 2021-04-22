@forelse ($results as $result)
<div class="item unselected">
    <h3>{{ $result->name }}</h3>
</div>
@empty
<p class="nothing">Nothing matches that search!</p>
@endforelse
