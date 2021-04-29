
// Method to get the search results from the allergen/ingredient search bars
function getResults(url, val, $resultContainer, forUser) {
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
                data: {
                    "search": val,
                    "forUser": forUser
                },
                success: function(data) {
                    // Hide the initial message
                    $resultContainer.find("p.initial-msg").fadeOut(transitionTime);
                    hideUnselected();
                    // Check to see if the "no results" message is shown
                    if (data == "<p class=\"nothing\">Nothing matches that search!</p>") {
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

// Method to hide (and then remove) any unselected items
function hideUnselected() {
    $(".unselected").fadeOut(transitionTime);
    setTimeout(function () {
        $(".unselected").remove();
    }, transitionTime+1);
}
