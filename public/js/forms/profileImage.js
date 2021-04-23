// Methods and Handlers to be called on or added to elements on page load/pageshow
$(window).on("load, pageshow", function() {
    // Handler to display the profile image upload/preview
    $("#profile-form input[type=\"file\"]").on("load change", function() {
        // Get the image container
        $container = $(this).parent().parent().parent();
        // Get the image tag
        $image = $container.find("img");
        // Add the image (as a preview) to the profile-image
        if (this.files && this.files[0]) {
            // Set the file as the first file
            $file = this.files[0];
            // Set image as FormData
            let formData = new FormData();
            formData.append('profile_image', $file);
            // Call on method to upload the image
            $.ajax({
                type : 'POST',
                url : "/Profile/Image/Add",
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $image.attr("src", e.target.result);
                    }
                    reader.readAsDataURL($file);
                },
                error: function(data) {
                    // Notify the user of failure
                    console.log("Image upload failed!");
                    console.log(data);
                }
            });
        }
    });
});
