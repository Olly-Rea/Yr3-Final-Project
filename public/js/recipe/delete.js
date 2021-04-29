// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {

    $("#delete").on("click", function() {
        showOverlay();
        $("#delete.prompt").fadeIn(transitionTime);
    });
    // Close alert prompt and fadeout
    $("#delete .close-prompt").on("click", function() {
        $("#delete.prompt").fadeOut(transitionTime);
        hideOverlay();
    });

});
