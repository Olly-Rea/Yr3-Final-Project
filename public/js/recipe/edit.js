// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    // Method to get the search results from the ingredient search bar
    $("#ingredient-search input").on("keyup keydown", function() {
        // Clear previous Timeout if user is still typing
        clearTimeout(searchTimeout);
        // Get the current value from the search bar
        val = $(this).val();
        // get the results from the query
        getResults("/Search/Ingredient", val, $("#ingredients-select"), 0)
    });

    // Item selection / deselection - Fridge Ingredients
    $("#ingredients-select").on("click", ".item.unselected", function() {
        // Get the id for this element
        id = $(this).children().first().val();
        index = $(".ingredient-panel").length;
        // Perform the ajax request
        $.ajax({
            type : "GET",
            url : "/Recipe/Add/Ingredient",
            data: {
                "id": id,
                "index": index
            },
            success: function(data) {
                $(this).remove();
                $("#ingredients").append(data)
            },
            error: function() {
                console.log("error!");
            }
        });
    });

    // Method to remove an ingredient input from the form
    $("#ingredients").on("click", ".remove", function() {
        $(this).parent().remove();
    });

    // Item selection / deselection - Fridge Ingredients
    $("#add-direction").on("click", function() {
        // Get the position to use for the direction
        index = ($("#directions .placeholder").length > 0) ? 1 : $("#directions").children().length;
        // Perform the ajax request
        $.ajax({
            type : "GET",
            url : "/Recipe/Add/Direction",
            data: {"index": index},
            success: function(data) {
                $("#directions").append(data);
                if ($("#directions .placeholder").length > 0) {
                    $("#directions .placeholder").fadeOut(transitionTime);
                    setTimeout(function () {
                        $(".direction-panel").fadeIn(transitionTime)
                    }, transitionTime+1);
                } else {
                    $(".direction-panel").fadeIn(transitionTime)
                }
                $("#add-direction").appendTo($("#directions"));
            },
            error: function() {
                console.log("error!");
            }
        });
    });

    // Method to remove a direction input from the form
    $("#directions").on("click", ".remove", function() {
        $(this).parent().remove();
        if ($(".direction-panel").length == 0) {
            $("#directions .placeholder").fadeIn(transitionTime);
        }
    });

    // Method to save the Recipe
    $("#save").on("click", function() {
        // Check there are inputs to submit
        if ($(".ingredient-panel").length == 0) {
            $("#alert.prompt > p.message").html("You need at least 1 ingredient to save this recipe!");
            showOverlay();
            $("#alert.prompt").fadeIn(transitionTime);
        } else if ($(".direction-panel").length == 0) {
            $("#alert.prompt > p.message").html("You need at least 1 direction to save this recipe!");
            showOverlay();
            $("#alert.prompt").fadeIn(transitionTime);
        } else {
            // Disable the search inputs
            $("#ingredient-search input, #ingredients-select input").prop('disabled', true);
            // Submit the form
            $("#recipe-form").submit();
        }
    });

    // Close alert prompt and fadeout
    $("#alert button").on("click", function() {
        $("#alert.prompt").fadeOut(transitionTime);
        hideOverlay();
    });

});

