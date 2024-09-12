jQuery(document).ready(function($) {
    // Use event delegation to handle click events on dynamically added elements
    $(document).on('click', '.cptp-add-preview-button', function() {
        let wrapper = $(this).prev('.cptp-preview-options-wrapper');
        let index = wrapper.find('.cptp-preview-option').length;
        let loop = $(this).closest('.woocommerce_variation').index();

        let newOption = `
            <div class="cptp-preview-option">
                <h4>Preview Option ${index + 1}</h4>
                <div class="form-row form-row-first">
                    <p class="form-field">
                        <label for="_custom_text_preview_input_type[${loop}][${index}]">Input Type</label>
                        <select id="_custom_text_preview_input_type[${loop}][${index}]" name="_custom_text_preview_input_type[${loop}][${index}]" class="cptp-input-type">
                            <option value="text">Text Input</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                    </p>
                </div>
                <div class="form-row form-row-last">
                    <p class="form-field">
                        <label for="_custom_text_preview_label[${loop}][${index}]">Label</label>
                        <input type="text" id="_custom_text_preview_label[${loop}][${index}]" name="_custom_text_preview_label[${loop}][${index}]" value="">
                    </p>
                </div>
                <div class="form-row">
                    <p class="form-field _custom_text_preview_dropdown_values[${loop}][${index}]_field">
                        <label for="_custom_text_preview_dropdown_values[${loop}][${index}]">Dropdown Values (comma separated)</label>
                        <input type="text" id="_custom_text_preview_dropdown_values[${loop}][${index}]" name="_custom_text_preview_dropdown_values[${loop}][${index}]" value="">
                    </p>
                </div>
                <div class="form-row">
                    <p class="form-field _custom_text_preview_font_size[${loop}][${index}]_field">
                        <label for="_custom_text_preview_font_size[${loop}][${index}]">Font Size</label>
                        <input type="number" id="_custom_text_preview_font_size[${loop}][${index}]" name="_custom_text_preview_font_size[${loop}][${index}]" value="">
                    </p>
                </div>
                <div class="form-row">
                    <p class="form-field">
                        <input type="checkbox" id="_custom_text_preview_render_on_circle[${loop}][${index}]" name="_custom_text_preview_render_on_circle[${loop}][${index}]" value="yes">
                        <label for="_custom_text_preview_render_on_circle[${loop}][${index}]">Render on Circle</label>
                    </p>
                </div>
                <div class="form-row">
                    <p class="form-field">
                        <input type="checkbox" id="_custom_text_preview_user_selected_font[${loop}][${index}]" name="_custom_text_preview_user_selected_font[${loop}][${index}]" value="yes">
                        <label for="_custom_text_preview_user_selected_font[${loop}][${index}]">User Selected Font</label>
                    </p>
                </div>
                <div class="form-row">
                    <div class="form-field-wrapper">
                        <p class="form-field form-field-wide">
                            <label for="_custom_text_preview_image_${loop}_${index}">Image</label>
                            <input type="hidden" id="_custom_text_preview_image_${loop}_${index}" name="_custom_text_preview_image[${loop}][${index}]" value="">
                        </p>
                        <p class="form-field form-field-wide">
                            <button type="button" class="button cptp-upload-image-button" data-target="#_custom_text_preview_image_${loop}_${index}">Select Image</button>
                        </p>
                        <p class="form-field form-field-wide">
                            <span class="cptp-image-preview"></span>
                        </p>
                    </div>
                </div>
                <button type="button" class="button cptp-delete-preview-button">Delete Preview</button>
                <hr>
            </div>
        `;

        wrapper.append(newOption);

        // Trigger change event on the form to enable the save button
        $('form.variations_form').trigger('change');
    });

    // Use event delegation to handle click events on delete buttons
    $(document).on('click', '.cptp-delete-preview-button', function() {
        $(this).closest('.cptp-preview-option').remove();

        // Trigger change event on the form to enable the save button
        $('form.variations_form').trigger('change');
    });

    // Use event delegation to handle change events on input type select elements
    $(document).on('change', '[id*="_custom_text_preview_input_type"]', function() {
        var selectedType = $(this).val();
        var dropdownValuesField = $(this).closest('.cptp-preview-option').find('[class*="_custom_text_preview_dropdown_values"]');

        if (selectedType === 'dropdown') {
            dropdownValuesField.show();
        } else {
            dropdownValuesField.hide();
        }
    });

    // Use event delegation to handle click events on upload image buttons
    $(document).on('click', '.cptp-upload-image-button', function(e) {
        e.preventDefault();

        let button = $(this);
        let target = $(button.data('target'));
        let preview = button.closest('.form-field-wrapper').find('.cptp-image-preview');

        // Create the media frame
        let frame = wp.media({
            title: 'Select or Upload an Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        // When an image is selected in the media frame...
        frame.on('select', function() {
            // Get the selected image
            let attachment = frame.state().get('selection').first().toJSON();

            // Update the hidden input field and the preview
            target.val(attachment.url);
            preview.html('<img src="' + attachment.url + '" style="max-width:100px;" />');

            // Trigger change event on the form to enable the save button
            $('form.variations_form').trigger('change');
        });

        // Finally, open the modal
        frame.open();
    });

    // Trigger change event on page load to show/hide dropdown values fields
    $('[id*="_custom_text_preview_input_type"]').trigger('change');
});