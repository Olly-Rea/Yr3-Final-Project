/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    JQuery 'Guest' doc for 3rd Year Project 'Recipe App'   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    //  Add 'show sign up' prompt handler to AI Chef (if user isn't logged in)
    $("#require-register").on("click", function() {
        showOverlay();
        $("#sign-up.prompt").fadeIn(transitionTime);
        $(".prompt").removeClass("hidden");
    });
    // Close prompt and fadeout
    $("#sign-up.prompt .close-prompt").on("click", function() {
        $("#sign-up.prompt").fadeOut(transitionTime);
        $(".prompt").addClass("hidden");
        hideOverlay();
    });
    $("#sign-up.prompt button").on("click", function() {
        window.location.href = '/register';
    });
});
