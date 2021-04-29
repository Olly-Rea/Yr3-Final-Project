// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    // Method to get the search results from the ingredient search bar
    $("#ingredient-search input").on("keyup keydown", function() {
        // Clear previous Timeout if user is still typing
        clearTimeout(searchTimeout);
        // Get the current value from the search bar
        val = $(this).val();
        // get the results from the query
        getResults("/Search/Ingredient", val, $("#fridge-ingredients"), 1)
    });

    // Item selection / deselection - Fridge Ingredients
    $("#fridge-ingredients").on("click", ".item.unselected", function() {
        addItem("/Fridge", $(this));
    });
    $("#fridge-ingredients").on("click", ".item.selected", function() {
        removeItem("/Fridge", $(this));
    });
});
