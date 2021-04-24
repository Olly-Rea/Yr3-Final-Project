// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    $("#show-feed").on("click", function () {
        window.location.href = 'CookBook';
    });
});
