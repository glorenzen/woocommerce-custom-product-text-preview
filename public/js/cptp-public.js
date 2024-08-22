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

    function toggleCustomTextOptions() {
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

    function setLogoFontFamily() {
        const selectedLogo = $('#pa_local-logo option:selected').text();
        const fontFamily = cptp_values.logo_font_mapping[selectedLogo] || cptp_values.logo_font_mapping['default'];
        return fontFamily;
    }

    function setNameFontFamily() {
        return $('#cptp-name-font option:selected').text();
    }

    // Check variations on change
    $(".variations select").change(function () {
        toggleCustomTextOptions();
    });

    // Initial check
    toggleCustomTextOptions();

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
        },
        error: function() {
            alert('An error occurred while fetching the settings.');
        }
    });

    $("#cptp-preview-city-text-button").click(function (event) {
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
                            customText: $("#cptp-custom-city-text").val(),
                            xCoordinate: settings.x_coordinate,
                            yCoordinate: settings.y_coordinate,
                            circleWidth: settings.circle_width,
                            fontSize: cptp_values.acf_fields.font_size_group.override_font_size ? cptp_values.acf_fields.font_size_group.font_size : settings.font_size,
                            fontColor: cptp_values.acf_fields.font_color_group.override_font_color ? cptp_values.acf_fields.font_color_group.font_color : settings.font_color,
                            fontFamily: setLogoFontFamily(),
                            circleColor: settings.circle_color
                        };

                        renderCanvas(canvas, imgElement, canvasSettings, { showCircle: false, renderOnCircle: true }, initialCanvasWidth, initialCanvasHeight);

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

    $("#cptp-preview-name-text-button").click(function (event) {
        event.preventDefault();
        
        const acfImage = cptp_values.acf_fields.field_name_text_preview_image.url;
        const imgElement = new Image();
        imgElement.src = acfImage;

        imgElement.onload = () => {
            canvas.clear();

            const image = new fabric.Image(imgElement, {
                left: 0,
                top: 0,
                selectable: false,
                scaleX: initialCanvasWidth / imgElement.width,
                scaleY: initialCanvasHeight / imgElement.height,
            });

            canvas.add(image);
            
            const canvasSettings = {
                customText: $("#cptp-custom-name-text").val(),
                xCoordinate: settings.x_coordinate,
                yCoordinate: settings.y_coordinate,
                fontSize: cptp_values.acf_fields.font_size_group.override_font_size ? cptp_values.acf_fields.font_size_group.font_size : settings.font_size,
                fontColor: cptp_values.acf_fields.font_color_group.override_font_color ? cptp_values.acf_fields.font_color_group.font_color : settings.font_color,
                fontFamily: setNameFontFamily(), 
            };

            renderCanvas(canvas, imgElement, canvasSettings, { showCircle: false, renderOnCircle: false }, initialCanvasWidth, initialCanvasHeight);

            modal.show();
            resizeCanvas();
        };
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
        let customCityText = $(".cptp-product-custom-text #cptp-custom-city-text").val();
        let customNameText = $(".cptp-product-custom-text #cptp-custom-name-text").val();

        $("<input />").attr("type", "hidden")
            .attr("name", "cptp_custom_city_text")
            .attr("value", customCityText)
            .appendTo(this);

        $("<input />").attr("type", "hidden")
            .attr("name", "cptp_custom_name_text")
            .attr("value", customNameText)
            .appendTo(this);
    });
});
