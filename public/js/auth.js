/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    JQuery 'Guest' doc for 3rd Year Project 'Recipe App'   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    //  Add 'show sign up' prompt handler to AI Chef (if user isn't logged in)
    $("#logout-link").on("click", function() {
        showOverlay();
        $("#logout.prompt").fadeIn(transitionTime);
    });
    // Close prompt and fadeout
    $("#logout .close-prompt").on("click", function() {
        $("#logout.prompt").fadeOut(transitionTime);
        hideOverlay();
    });
});

