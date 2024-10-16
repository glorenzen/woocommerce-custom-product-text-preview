jQuery(document).ready(function ($) {
    const canvas = new fabric.Canvas("cptp-canvas");
    const initialCanvasWidth = canvas.width;
    const initialCanvasHeight = canvas.height;
    const modal = $("#cptp-preview-modal");

    // Create hidden input field on document ready
    const hiddenInput = $("<input />").attr("type", "hidden")
                                      .attr("name", "preview_options_data")
                                      .appendTo("form.cart");

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
        const selectedLogo = $('#pa_craft-logo option:selected').text();
        const fontFamily = cptp_values.logo_font_mapping[selectedLogo] || cptp_values.logo_font_mapping['default'];
        return fontFamily;
    }

    function setSelectedFontFamily(variationId, index) {
        return $(`#cptp-font-select-${variationId}-${index} option:selected`).text();
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

    // Handle preview button click
    $(document).on('click', '.cptp-preview-text-button', function (event) {
        event.preventDefault();

        const button = $(this);
        const variationId = selectedVariationId;
        const index = button.attr('id').split('-').pop();

        const imageUrl = variationPreviewOptions[variationId].options[index].image;

        const imgElement = new Image();
        imgElement.src = imageUrl;

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

            const renderOnCircle = variationPreviewOptions[variationId].options[index].render_on_circle === 'yes' ? true : false;
            const fontSize = variationPreviewOptions[variationId].options[index].font_size;
            const fontColor = variationPreviewOptions[variationId].options[index].font_color;

            const fontSelect = $(`#cptp-font-select-${variationId}-${index}`);

            let canvasSettings = {
                customText: $(`#cptp-custom-text-${variationId}-${index}`).val(),
                xCoordinate: settings.x_coordinate,
                yCoordinate: settings.y_coordinate,
                fontSize: fontSize,
                fontColor: fontColor,
                fontFamily: fontSelect.length > 0 ? setSelectedFontFamily(variationId, index) : setLogoFontFamily(),
            };

            if (renderOnCircle) {
                canvasSettings.circleWidth = settings.circle_width;
            }

            renderCanvas(canvas, imgElement, canvasSettings, { showCircle: false, renderOnCircle: renderOnCircle }, initialCanvasWidth, initialCanvasHeight);

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

    function updatePreviewOptions(variationId) {
        let container = $('#cptp-preview-options-container');
        container.empty();

        if (variationPreviewOptions.hasOwnProperty(variationId)) {
            let previewOptions = variationPreviewOptions[variationId].options;
            let previewTitle = variationPreviewOptions[variationId].title || '';

            if (previewTitle) {
                container.append(`<h3 class="cptp-options-title">${previewTitle}</h3>`);
            }

            previewOptions.forEach(function(option, index) {
                const inputType = option.input_type || 'text';
                const label = option.label || '';
                const description = option.description || '';
                const userSelectedFont = option.user_selected_font || 'no';
                const dropdownOptions = (option.dropdown_values || '').split(',').map(value => value.trim());

                let newOption = '';

                if (description) {
                    newOption += `       
                        <p class="cptp-preview-description">${description}</p>
                    `;
                }

                newOption += `
                    <div class="cptp-input-wrapper">
                        <label for="cptp-custom-text-${variationId}-${index}" class="cptp-form-label">${label}</label>
                        ${inputType === 'text' ? `<input type="text" id="cptp-custom-text-${variationId}-${index}" name="cptp-custom-text-${variationId}-${index}" data-label="${label}" class="cptp-form-control" value="" maxLength="${settings.custom_text_max_length}" />` : ''}
                        ${inputType === 'dropdown' ? `<select id="cptp-custom-text-${variationId}-${index}" name="cptp-custom-text-${variationId}-${index}" data-label="${label}" class="cptp-form-control">${dropdownOptions.map(dropdownOption => `<option value="${dropdownOption}">${dropdownOption}</option>`).join('')}</select>` : ''}
                    </div>
                `;


                if (userSelectedFont === 'yes') {
                    newOption += `
                        <div class="cptp-input-wrapper">
                            <label for="cptp-font-select-${variationId}-${index}" class="cptp-form-label">${option.label} - Custom Font</label>
                            <select id="cptp-font-select-${variationId}-${index}" name="cptp-font-select-${variationId}-${index}" data-label="${option.label} - Custom Font" class="cptp-form-control">
                                ${fontOptions.map(font => `<option value="${font}">${font}</option>`).join('')}
                            </select>
                        </div>
                    `;
                }

                newOption += `
                    <div class="cptp-input-wrapper cptp-button-wrapper">
                        <button id="cptp-preview-text-button-${variationId}-${index}" class="cptp-button cptp-preview-text-button">Preview</button>
                    </div>
                `;

                container.append(newOption);
            });
        }
    }

    // Update preview options when a variation is selected
    $('form.variations_form').on('show_variation', function(event, variation) {
        updatePreviewOptions(variation.variation_id);
        $('.cptp-product-custom-text').show();
    });

    // Hide preview options when no variation is selected
    $('form.variations_form').on('hide_variation', function() {
        $('#cptp-preview-options-container').empty();
        $('.cptp-product-custom-text').hide();
    });

    // Update hidden input field with preview options data
    $(document).on('change', '.cptp-input-wrapper input, .cptp-input-wrapper select', function() {
        const previewOptions = $(".cptp-input-wrapper input, .cptp-input-wrapper select");
        let previewOptionsData = [];

        previewOptions.each(function() {
            const input = $(this);
            const inputName = input.attr("name");
            const label = input.data("label");
            const inputValue = input.val();

            previewOptionsData.push({
                name: inputName,
                label: label,
                value: inputValue
            });
        });

        const previewOptionsJson = JSON.stringify(previewOptionsData);
        hiddenInput.val(previewOptionsJson);
    });
});