var updateTimeout = setTimeout(null);

function updatePrefs() {
    // Clear previous Timeout if user is still deciding
    clearTimeout(updateTimeout);
    updateTimeout = setTimeout(function () {
        // Get the values from the preferences form
        vals = $("#user-prefs input[type=\"number\"]").serializeArray();
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
            error: function(data) {
                console.log(data);
            }
        });
    }, 400);
}
