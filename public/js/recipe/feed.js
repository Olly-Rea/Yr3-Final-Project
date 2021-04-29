// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {

    $("#cookbook-refresh").on("click", function () {
        $this = $(this)
        // Disable click events
        $this.css("pointer-events", "none");
        // Show loading animation
        $this.addClass("active");
        // Fetch New Recipes
        $.ajax({
            type : "GET",
            url : "/CookBook/Fetch",
            success: function(data) {
                // Remove all existing recipe panels and add the new recipe panels
                $(".recipe-panel").remove();
                setTimeout(function () {
                    $("main").append(data);
                    $this.appendTo("main");
                }, 1);
                // Allow animation to play at least once
                setTimeout(function () {
                    $this.removeClass("active");
                    // re-enable click events
                    $this.css("pointer-events", "auto");
                }, 2000);
            }, error: function(data) {

                console.log(data);

                // Allow animation to play at least once
                setTimeout(function () {
                    $this.removeClass("active");
                    // re-enable click events
                    $this.css("pointer-events", "auto");
                }, 2000);
            }
        });
    });
});
