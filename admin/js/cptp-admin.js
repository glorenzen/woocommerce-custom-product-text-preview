jQuery(document).ready(function($) {
    // Uploading files
    let file_frame;
    let wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    let set_to_post_id = 0; // Set this

    const canvas = new fabric.Canvas('cptp-preview-canvas');

    // Function to load image into canvas
    function loadImageToCanvas(url) {
        fabric.Image.fromURL(url, function(img) {
            canvas.clear();
            img.scaleToWidth(canvas.width);
            img.scaleToHeight(canvas.height);
            canvas.add(img);
            canvas.renderAll();
        });
    }

    // Load the image on screen load if URL is present
    let initialImageUrl = $('input[name="cptp_preview_image"]').val();
    if (initialImageUrl) {
        loadImageToCanvas(initialImageUrl);
    }

    jQuery('.cptp-upload-button').on('click', function(event) {
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            // Set the post ID to what we want
            file_frame.uploader.uploader.param('post_id', set_to_post_id);
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select an image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: false // Only allow one file to be uploaded
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            // We set multiple to false so only get one image from the uploader
            var attachment = file_frame.state().get('selection').first().toJSON();

            // Do something with attachment.id and/or attachment.url here
            $('input[name="cptp_preview_image"]').val(attachment.url);

            // Load the image into the Fabric.js canvas
            loadImageToCanvas(attachment.url);

            // Restore the main post ID
            wp.media.model.settings.post.id = wp_media_post_id;
        });

        // Finally, open the modal
        file_frame.open();
    });

    // Restore the main ID when the add media button is pressed
    jQuery('a.add_media').on('click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});