/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    JQuery Global doc for 3rd Year Project 'Recipe App'    *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
var menuTransitionTime = 550;
var transitionTime = 200;
var searchTimeout = setTimeout(null);

// get the CSRF token for JQuery AJAX
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {

    $("#site-overlay-background").on("click", function() {
        // Clear the search bar if it contains a value
        if ($("#search-bar").val() != "") {
            $("#search-bar").val("");
        }
        hideOverlay();
    });

});

// Methods to show/hide the site overlay
function showOverlay() {
    // Fade out the site overlay and close any prompts
    $("body").addClass("no-scroll");
    $("#site-overlay").fadeIn(transitionTime);
    setTimeout(function () {
        $(".prompt").removeClass("hidden");
    }, 1);
}
function hideOverlay() {
    // Fade out the site overlay and close any prompts
    $(".prompt").addClass("hidden");
    $("#site-overlay, #results-container").fadeOut(transitionTime);
    $("body").removeClass("no-scroll");
}
