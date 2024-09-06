<?php
// Check if the product has custom text enabled
if (get_field('has_custom_text')) {
    // Retrieve the max length for the custom text input from the admin settings
    $custom_text_max_length = get_option('cptp_custom_text_max_length', 20); // Default to 20 if not set
    $name_text_max_length = get_option('cptp_name_text_max_length', 20); // Default to 20 if not set
    $font_options = array('Graduate', 'Monoton', 'Vast Shadow', 'Rye', 'UnifrakturMaguntia');

    // Retrieve label settings
    $name_text_label = get_option('cptp_name_text_label', 'Name Text');
    $name_font_label = get_option('cptp_name_font_label', 'Name Font');
    $city_text_label = get_option('cptp_logo_text_label', 'Logo Text');
    ?>
    <div class="cptp-product-custom-text" style="display: none;">
        <div class="cptp-input-wrapper">
            <label for="cptp-name_text" class="cptp-form-label"><?php echo esc_html($name_text_label); ?></label>
            <input type="text" id="cptp-custom-name-text" name="cptp-name_text" class="cptp-form-control" value="" maxLength="<?php echo esc_attr($name_text_max_length); ?>" />
        </div>
        <div class="cptp-input-wrapper">
            <label for="cptp-name-font" class="cptp-form-label"><?php echo esc_html($name_font_label); ?></label>
            <select id="cptp-name-font" name="cptp-name-font" class="cptp-form-control">
                <?php foreach ($font_options as $font) : ?>
                    <option value="<?php echo esc_attr($font); ?>"><?php echo esc_html($font); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="cptp-input-wrapper cptp-button-wrapper">
            <button id="cptp-preview-name-text-button" class="cptp-button">Preview</button>
        </div>
        <div class="cptp-input-wrapper">
            <label for="cptp-custom_text" class="cptp-form-label"><?php echo esc_html($city_text_label); ?></label>
            <input type="text" id="cptp-custom-city-text" name="cptp-custom_text" class="cptp-form-control" value="" maxLength="<?php echo esc_attr($custom_text_max_length); ?>" />
        </div>  
        <div class="cptp-input-wrapper cptp-button-wrapper">
            <button id="cptp-preview-city-text-button" class="cptp-button">Preview</button>
        </div>
    </div>
    <?php
}
?>