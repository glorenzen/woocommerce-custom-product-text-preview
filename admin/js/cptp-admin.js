jQuery(document).ready(function($) {
    const canvas = new fabric.Canvas('cptp-preview-canvas');
    const imgElement = new Image();
    const initialImageUrl = $('input[name="cptp_preview_image"]').val() || "https://via.placeholder.com/500x500";
    imgElement.src = initialImageUrl;

    let scaleX, scaleY;

    if (imgElement.complete) {
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
    } else {
        console.error("Failed to load image");
    }

    $('.cptp-upload-button').on('click', function(event) {
        event.preventDefault();
        console.log("Upload button clicked");
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

    $('#render-button').on('click', function() {
        const text = $('input[name="cptp_custom_text"]').val();
        const x = parseInt($('input[name="cptp_x_coordinate"]').val(), 10) || imgElement.width / 2;
        const y = parseInt($('input[name="cptp_y_coordinate"]').val(), 10) || imgElement.height / 2;
        const circleWidth = parseInt($('input[name="cptp_circle_width"]').val(), 10) || 200;

        canvas.getObjects().forEach((obj) => {
            if (obj.type === 'text' || obj.type === 'path') {
                canvas.remove(obj);
            }
        });

        const scaledX = x * scaleX;
        const scaledY = y * scaleY;

        const radius = circleWidth / 2;
        const pathData = `M ${scaledX - radius}, ${scaledY} a ${radius},${radius} 0 1,0 ${circleWidth},0`;

        const halfCirclePath = new fabric.Path(pathData, {
            fill: '',
            stroke: 'black',
            selectable: false,
            originX: 'center',
            left: scaledX,
            top: scaledY,
        });

        const pathInfo = fabric.util.getPathSegmentsInfo(halfCirclePath.path);
        halfCirclePath.segmentsInfo = pathInfo;
        const pathLength = pathInfo[pathInfo.length - 1].length;
        const fontSize = (2.25 * pathLength) / text.length;

        const textInstance = new fabric.Text(text, {
            fontSize: fontSize,
            fill: 'white',
            textAlign: 'center',
            originX: 'center',
            path: halfCirclePath,
            pathSide: 'left',
            pathAlign: 'center',
            pathOffset: radius,
        });

        textInstance.set({
            left: scaledX,
            top: scaledY,
        });

        textInstance.setCoords();

        canvas.add(halfCirclePath);
        canvas.add(textInstance);
        canvas.renderAll();
    });
});