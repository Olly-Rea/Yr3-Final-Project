var slideTimeout = setTimeout(null);

// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    // Preference sliders
    $(".slider-container").each(function(){
        let $this = $(this);
        let val = $this.children().first().val();
        $this.slider({
            range: "min",
            min: 0,
            max: 10,
            value: val,
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
});
