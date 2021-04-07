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

});
