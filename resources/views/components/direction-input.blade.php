<div class="direction-panel" style="display: none">
    <h3 class="step">{{ $index }}</h3>
    <input type="text" name="instruction[{{ $index }}]" class="content" placeholder="Start typing your direction here..." onfocus="this.placeholder = ''" onfocusout="this.placeholder = 'Start typing your direction here...'">
    <div class="remove">
        <svg>
            <use xlink:href="{{ secure_asset('images/graphics/remove.svg#icon') }}"></use>
        </svg>
    </div>
</div>
