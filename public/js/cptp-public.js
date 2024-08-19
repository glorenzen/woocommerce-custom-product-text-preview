jQuery(document).ready(function ($) {
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

    const modal = $("#cptp-preview-modal");
    const canvas = new fabric.Canvas("cptp-canvas");

    $("#cptp-preview-text-button").click(function (event) {
        event.preventDefault();
        const variationId = selectedVariationId;

        $.ajax({
            url: cptp_ajax.ajax_url,
            method: "POST",
            data: {
                action: "get_featured_image",
                variation_id: variationId,
            },
            success: function (response) {
                if (response.success) {
                    const featuredImage = response.data;
                    const imgElement = new Image();
                    imgElement.src = featuredImage;

                    imgElement.onload = () => {
                        const image = new fabric.Image(imgElement, {
                            left: 0,
                            top: 0,
                            selectable: false,
                            scaleX: canvas.width / imgElement.width,
                            scaleY: canvas.height / imgElement.height,
                        });

                        canvas.add(image);
                        
                        const canvasSettings = {
                            customText: $("#cptp-custom-text").val(),
                            xCoordinate: settings.x_coordinate,
                            yCoordinate: settings.y_coordinate,
                            circleWidth: settings.circle_width,
                            fontSize: settings.font_size,
                            fontColor: settings.font_color,
                            fontFamily: settings.font_family || "Open Sans",
                            circleColor: settings.circle_color
                        };

                        renderCanvas(canvas, imgElement, canvasSettings, { showCircle: false });

                        modal.show();
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
