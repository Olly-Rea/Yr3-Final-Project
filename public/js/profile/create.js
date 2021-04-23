/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *     JQuery for adding profile information on creation     *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
var slideTimeout = setTimeout(null);

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
    // Handler to display the profile image upload/preview
    $("#profile-form input[type=\"file\"]").on("load change", function() {
        // Get the image container
        $container = $(this).parent().parent().parent();
        // Get the image tag
        $image = $container.find("img");
        // Add the image (as a preview) to the profile-image
        if (this.files && this.files[0]) {
            // Set the file as the first file
            $file = this.files[0];
            // Set image as FormData
            let formData = new FormData();
            formData.append('profile_image', $file);
            // Call on method to upload the image
            $.ajax({
                type : 'POST',
                url : "/Profile/Image/Add",
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $image.attr("src", e.target.result);
                    }
                    reader.readAsDataURL($file);
                },
                error: function(data) {
                    // Notify the user of failure
                    console.log("Image upload failed!");
                    console.log(data);
                }
            });
        }
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

    // Preference sliders
    $(".slider-container").each(function(){
        var $this = $(this);
        $this.slider({
            range: "min",
            min: 0,
            max: 10,
            value: 5,
            // Values to add smooth scroll
            step: .0001,
            animate: transitionTime,
            slide: function(event, ui) {
                // Clear the slideTimeout
                clearTimeout(slideTimeout);
            },
            stop: function(event, ui) {
                // Set the new value
                value = Math.round(ui.value);
                // Snap to position if user has stopped sliding (based on slider position)
                slideTimeout = setTimeout(function () {
                    $this.slider("value", value, "animate", transitionTime*2);
                }, transitionTime*2);
                // Change the input value
                $this.children().first().attr("value", value);
            }
        });
    });

    // Fridge form "page"
    $("#fridge-form .nav-items h3#complete").on("click", function() {
        // If the user has included at least 3 items in their fridge
        if($("#fridge-ingredients").find(".selected").length < 3) {
            // Prepare the action prompt messages
            $("#action.prompt .prompt-title").html("Ingredients required!");
            $("#action.prompt .message").html("Please add at least 3 ingredients to proceed!");
            $("#action.prompt button").on("click", function() {
                $("#action.prompt").addClass("hidden");
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

    // Method to get the search results from the allergen search bar
    $("#allergen-search input").on("keyup keydown", function() {
        // Clear previous Timeout if user is still typing
        clearTimeout(searchTimeout);
        // Get the current value from the search bar
        val = $(this).val();
        // get the results from the query
        getResults("/Search/Allergen", val, $("#profile-allergens"))
    });
    // Method to get the search results from the ingredient search bar
    $("#ingredient-search input").on("keyup keydown", function() {
        // Clear previous Timeout if user is still typing
        clearTimeout(searchTimeout);
        // Get the current value from the search bar
        val = $(this).val();
        // get the results from the query
        getResults("/Search/Ingredient", val, $("#fridge-ingredients"))
    });

    // Item selection / deselection - Allergens
    $("#profile-allergens").on("click", ".item.unselected", function() {
        addItem("/Allergen", $(this));
    });
    $("#profile-allergens").on("click", ".item.selected", function() {
        removeItem("/Allergen", $(this));
    });
    // Item selection / deselection - Fridge Ingredients
    $("#fridge-ingredients").on("click", ".item.unselected", function() {
        addItem("/Fridge", $(this));
    });
    $("#fridge-ingredients").on("click", ".item.selected", function() {
        removeItem("/Fridge", $(this));
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

// Method to hide (and then remove) any unselected items
function hideUnselected() {
    $(".unselected").fadeOut(transitionTime);
    setTimeout(function () {
        $(".unselected").remove();
    }, transitionTime+1);
}

// Method to clear the search bars
function clearSearches() {
    setTimeout(function () {
        $("#allergen-search input, #ingredient-search input").val("");
    }, transitionTime);
}

// Method to get the search results from the allergen/ingredient search bars
function getResults(url, val, $resultContainer) {
    // Prevent spacebar input until other characters have been typed
    if(val.length == 0) {
        var e = window.event || e;
        var key = e.keyCode;
        if (key == 32) { //spacebar
            e.preventDefault();
        }
    }
    // Only start searching if searchBar value > 2
    if(val.length > 1) {
        // Start the search Timeout
        searchTimeout = setTimeout(function () {
            // When user has finished typing, search databases and show relevent data
            $.ajax({
                type : "GET",
                url : url,
                data: {"search": val},
                success: function(data) {
                    // Hide the initial message
                    $resultContainer.find("p.initial-msg").fadeOut(transitionTime);
                    hideUnselected();
                    // Check to see if the "no results" message is shown
                    if (data == "<p class=\"nothing\">Nothing matches that search!</p>") {
                        //  && $resultContainer.find("p.nothing").length == 0) {

                        console.log(data);

                        setTimeout(function () {
                            $resultContainer.append(data);
                        }, transitionTime+1);
                    } else {
                        $("p.nothing").fadeOut(transitionTime);
                        setTimeout(function () {
                            $("p.nothing").remove();
                            $resultContainer.append(data);
                        }, transitionTime+1);
                    }

                }
            });
        }, 400);
    } else {
        searchTimeout = setTimeout(function () {
            // Hide (and then remove any unselected items)
            $(".unselected, p.nothing").fadeOut(transitionTime);
            setTimeout(function () {
                $resultContainer.find(".unselected, p.nothing").remove();
                if ($(".selected").length == 0) {
                    $resultContainer.find("p.initial-msg").fadeIn(transitionTime);
                }
            }, transitionTime+1);
        }, transitionTime+1);
    }
}

// Method to add an item to the users account
function addItem(type, $item) {
    // Get the id for this element
    id = $item.children().first().val();
    // Perform the ajax request
    $.ajax({
        type : "GET",
        url : type+"/Add",
        data: {"id": id},
        success: function() {
            $item.removeClass("unselected");
            $item.addClass("selected");
        },
        error: function() {
            console.log("error!");
        }
    });
}
// Method to remove an item from the users account
function removeItem(type, $item) {
    // Get the id for this element
    id = $item.children().first().val();
    // Perform the ajax request
    $.ajax({
        type : "GET",
        url : type+"/Remove",
        data: {"id": id},
        success: function() {
            $item.removeClass("selected");
            $item.addClass("unselected");
        },
        error: function() {
            console.log("error!");
        }
    });
}
