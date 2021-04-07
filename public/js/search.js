/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *     JS for 3rd Year Project 'Recipe App' Search bar     *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
var $searchBar;
var searchTimeout = setTimeout(null);

// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {

    // Set the $searchBar variable
    $searchBar = $("#search-bar");

    $searchBar.on("focus keyup keydown", function() {
        // Clear previous Timeout if user is still typing
        clearTimeout(searchTimeout);

        // Prevent spacebar input until other characters have been typed
        if($searchBar.val().length == 0) {
            var e = window.event || e;
            var key = e.keyCode;
            if (key == 32) { //spacebar
                e.preventDefault();
            }
        }

        // Only start searching if searchBar value > 0
        if($searchBar.val().length > 0) {
            // Hide the "quick search shortcut" links and
            $("#search-items, #search-content > .screen-seperator").fadeOut(menuTransitionTime);

            // Start the search Timeout
            searchTimeout = setTimeout(function () {
                // When user has finished typing, search databases and show relevent data
                $.ajax({
                    type : 'get',
                    url : "/search",
                    data: {'search': $searchBar.val()},
                    success: function(data) {
                        setTimeout(function () {
                            $('#results-container').html(data);
                            $(".search-panel").fadeIn(transitionTime);
                        }, transitionTime+1);
                    }
                });
            }, 400);
        // Else reset the results output
        } else {
            // Hide any currently shown search-panels
            $(".search-panel").fadeOut(transitionTime);
            // Start the search Timeout
            searchTimeout = setTimeout(function () {
                if ($('#results-container > p').length == 0) {
                    $('#results-container').html("<p style=\"display:none\">Start typing to search profiles!</p>");
                    $("#results-container > p, #search-items, #search-content > .screen-seperator").fadeIn(menuTransitionTime);
                }
            }, transitionTime+1);
        }
    });
});


