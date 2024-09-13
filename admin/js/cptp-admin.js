jQuery(document).ready(function($) {
    const canvas = new fabric.Canvas('cptp-preview-canvas');
    const imgElement = new Image();
    const initialImageUrl = $('input[name="cptp_preview_image"]').val() || "https://via.placeholder.com/500x500";
    imgElement.src = initialImageUrl;

    let scaleX, scaleY;

    function updateCanvas() {
        const settings = {
            customText: $('input[name="cptp_custom_text"]').val(),
            xCoordinate: $('input[name="cptp_x_coordinate"]').val(),
            yCoordinate: $('input[name="cptp_y_coordinate"]').val(),
            circleWidth: $('input[name="cptp_circle_width"]').val(),
            fontSize: $('input[name="cptp_preview_font_size"]').val(),
            fontColor: $('input[name="cptp_font_color"]').val(),
            fontFamily: $('select[name="cptp_font_family"]').val() || 'Open Sans',
            circleColor: $('input[name="cptp_circle_color"]').val()
        };

        renderCanvas(canvas, imgElement, settings, { showCircle: true, renderOnCircle: true });
    }

    imgElement.onload = () => {
        scaleX = canvas.width / imgElement.width;
        scaleY = canvas.height / imgElement.height;

        $('#image-size').text(`Image Size: ${imgElement.width}x${imgElement.height}`);

        const imgInstance = new fabric.Image(imgElement, {
            left: 0,
            top: 0,
            selectable: false,
            scaleX: scaleX,
            scaleY: scaleY,
        });
        canvas.add(imgInstance);
        updateCanvas();
    };

    $('.cptp-upload-button').on('click', function(event) {
        event.preventDefault();
        let file_frame;
        const set_to_post_id = 0;

        if (file_frame) {
            file_frame.uploader.uploader.param('post_id', set_to_post_id);
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select an image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: false
        });

        file_frame.on('select', function() {
            const attachment = file_frame.state().get('selection').first().toJSON();
            $('input[name="cptp_preview_image"]').val(attachment.url);
            imgElement.src = attachment.url;
            imgElement.onload();
        });

        file_frame.open();
    });

    $('input[name="cptp_custom_text"], input[name="cptp_x_coordinate"], input[name="cptp_y_coordinate"], input[name="cptp_circle_width"], input[name="cptp_preview_font_size"], input[name="cptp_font_color"], select[name="cptp_font_family"], input[name="cptp_circle_color"]').on('input', updateCanvas);
});