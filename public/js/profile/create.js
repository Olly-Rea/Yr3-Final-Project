/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *     JQuery for adding profile information on creation     *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {

    // Click handlers to change pages
    $("#greeting-page button").on("click", function() {
        changePage("#greeting-page", "#profile-form", 1);
    });

    // Profile form "page"
    $("#profile-form .nav-items h3.next").on("click", function() {
        changePage("#profile-form", "#allergen-form", 2);
    });

    // Allergens form "page"
    $("#allergen-form .nav-items h3.next").on("click", function() {
        changePage("#allergen-form", "#prefs-form", 3);
        clearSearches();
    });
    $("#allergen-form .nav-items h3.back").on("click", function() {
        changePage("#allergen-form", "#profile-form", 1);
    });

    // Preferences form "page"
    $("#prefs-form .nav-items h3.next").on("click", function() {
        changePage("#prefs-form", "#fridge-form", 4);
    });
    $("#prefs-form .nav-items h3.back").on("click", function() {
        changePage("#prefs-form", "#allergen-form", 2);
    });

    // Fridge form "page"
    $("#fridge-form .nav-items h3#complete").on("click", function() {
        // If the user has included at least 3 items in their fridge
        if($("#fridge-ingredients").find(".selected").length < 3) {
            // Prepare the action prompt messages
            $("#action.prompt .prompt-title").html("Ingredients required!");
            $("#action.prompt .message").html("Please add at least 3 ingredients to proceed!");
            $("#action.prompt button").on("click", function() {
                hideOverlay();
            })
            // Show the overlay and action prompt
            $("#action.prompt").fadeIn(transitionTime);
            showOverlay()
        } else {
            // Get the values from the preferences form
            vals = $("#prefs-form input[type=\"number\"]").serializeArray();
            // Submit the "setup complete" request and allow the user to proceed
            $.ajax({
                type : "GET",
                url : "/Prefs/Update",
                data: {
                    "spice": vals[0]["value"],
                    "sweet": vals[1]["value"],
                    "sour": vals[2]["value"],
                    "diff": vals[3]["value"]
                },
                success: function() {
                    window.location.href = "/Me";
                }
            });
        }
    });
    $("#fridge-form .nav-items h3.back").on("click", function() {
        changePage("#fridge-form", "#prefs-form", 3);
        clearSearches();
    });

});

// Function to set the width of the progress bar
function makeProgress(num) {
    width = $("#site-links").outerWidth()*(0.25*num);
    // Slightly tweak width for these increments
    if (num == 1) width += 20;
    if (num == 3) width -= 6;
    // Animate the progress bar increment
    $("#progress").animate({width: width}, transitionTime);
}

// Function to change pages
function changePage(current, next, pageNum) {
    $(current).fadeOut(transitionTime);
    setTimeout(function () {
        hideUnselected();
        $(next).fadeIn(transitionTime);
    }, transitionTime+1);
    // Set the width of the progress bar
    makeProgress(pageNum);
}

// Method to clear the search bars
function clearSearches() {
    setTimeout(function () {
        $("#allergen-search input, #ingredient-search input").val("");
    }, transitionTime);
}

