// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    // Method to get the search results from the allergen search bar
    $("#allergen-search input").on("keyup keydown", function() {
        // Clear previous Timeout if user is still typing
        clearTimeout(searchTimeout);
        // Get the current value from the search bar
        val = $(this).val();
        // get the results from the query
        getResults("/Search/Allergen", val, $("#profile-allergens"))
    });

    // Item selection / deselection - Allergens
    $("#profile-allergens").on("click", ".item.unselected", function() {
        addItem("/Allergen", $(this));
    });
    $("#profile-allergens").on("click", ".item.selected", function() {
        removeItem("/Allergen", $(this));
    });
});
