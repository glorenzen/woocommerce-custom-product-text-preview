jQuery(document).ready(function ($) {
    const canvas = new fabric.Canvas("cptp-canvas");
    const initialCanvasWidth = canvas.width;
    const initialCanvasHeight = canvas.height;
    const modal = $("#cptp-preview-modal");

    function checkVariationsSelected() {
        let allSelected = true;
        $(".variations select").each(function () {
            if ($(this).val() === "") {
                allSelected = false;
            }
        });
        return allSelected;
    }

    function toggleCustomTextField() {
        if (checkVariationsSelected()) {
            $(".cptp-product-custom-text").show();
        } else {
            $(".cptp-product-custom-text").hide();
        }
    }

    function resizeCanvas() {
        const outerCanvasContainer = $('.cptp-modal-content');
        const ratio          = canvas.getWidth() / canvas.getHeight();
        const containerWidth = outerCanvasContainer.width();
        const scale          = containerWidth / canvas.getWidth();
        const zoom           = canvas.getZoom() * scale;

        canvas.setDimensions({ width: containerWidth, height: containerWidth / ratio });
        canvas.setViewportTransform([zoom, 0, 0, zoom, 0, 0]);
    }

    // Check variations on change
    $(".variations select").change(function () {
        toggleCustomTextField();
    });

    // Initial check
    toggleCustomTextField();

    let selectedVariationId;

    $("form.variations_form").on("show_variation", function (event, variation) {
        selectedVariationId = variation.variation_id;
    });

    // Fetch settings from REST API
    let settings = {};
    $.ajax({
        url: '/wp-json/cptp/v1/settings',
        method: 'GET',
        success: function(response) {
            settings = response;
            console.log(settings);
        },
        error: function() {
            alert('An error occurred while fetching the settings.');
        }
    });

    $("#cptp-preview-text-button").click(function (event) {
        event.preventDefault();
        const variationId = selectedVariationId;

        $.ajax({
            url: cptp_values.ajax_url,
            method: "POST",
            data: {
                action: "get_featured_image",
                variation_id: variationId,
            },
            success: function (response) {
                if (response.success) {
                    canvas.clear();

                    const featuredImage = response.data;
                    const imgElement = new Image();
                    imgElement.src = featuredImage;

                    imgElement.onload = () => {
                        const image = new fabric.Image(imgElement, {
                            left: 0,
                            top: 0,
                            selectable: false,
                            scaleX: initialCanvasWidth / imgElement.width,
                            scaleY: initialCanvasHeight / imgElement.height,
                        });

                        canvas.add(image);
                        
                        const canvasSettings = {
                            customText: $("#cptp-custom-text").val(),
                            xCoordinate: settings.x_coordinate,
                            yCoordinate: settings.y_coordinate,
                            circleWidth: settings.circle_width,
                            fontSize: cptp_values.acf_fields.font_size_group.override_font_size ? cptp_values.acf_fields.font_size_group.font_size : settings.font_size,
                            fontColor: cptp_values.acf_fields.font_color_group.override_font_color ? cptp_values.acf_fields.font_color_group.font_color : settings.font_color,
                            fontFamily: cptp_values.acf_fields.font_family_group.override_font_family ? cptp_values.acf_fields.font_family_group.font_family : settings.font_family || "Open Sans",
                            circleColor: settings.circle_color
                        };

                        renderCanvas(canvas, imgElement, canvasSettings, { showCircle: false }, initialCanvasWidth, initialCanvasHeight);

                        modal.show();
                        resizeCanvas();
                    };
                } else {
                    alert("Error: " + response.data);
                }
            },
            error: function () {
                alert("An error occurred while fetching the featured image.");
            },
        });
    });

    $(".cptp-close").click(function () {
        modal.hide();
    });

    $(window).click(function (event) {
        if (event.target.id === "cptp-preview-modal") {
            modal.hide();
        }
    });

    // Capture custom text value on add to cart
    $("form.cart").on("submit", function () {
        let customText = $(".cptp-product-custom-text input").val();
        $("<input />").attr("type", "hidden")
            .attr("name", "cptp_custom_text")
            .attr("value", customText)
            .appendTo(this);
    });
});
