<?php
// Check if the product has custom text enabled
if (get_field('has_custom_text')) {
    // Retrieve the max length for the custom text input from the admin settings
    $custom_text_max_length = get_option('cptp_custom_text_max_length', 20); // Default to 20 if not set
    $name_text_max_length = get_option('cptp_name_text_max_length', 20); // Default to 20 if not set
    ?>
    <div class="cptp-product-custom-text" style="display: none;">
        <div class="cptp-input-wrapper">
            <label for="cptp-name_text" class="cptp-form-label">Name Text</label>
            <input type="text" id="cptp-custom-name-text" name="cptp-name_text" class="cptp-form-control" value="" maxLength="<?php echo esc_attr($name_text_max_length); ?>" />
        </div>
        <div class="cptp-input-wrapper">
            <button id="cptp-preview-name-text-button" class="cptp-button">Preview</button>
        </div>
        <div class="cptp-input-wrapper">
            <label for="cptp-custom_text" class="cptp-form-label">City Text</label>
            <input type="text" id="cptp-custom-city-text" name="cptp-custom_text" class="cptp-form-control" value="" maxLength="<?php echo esc_attr($custom_text_max_length); ?>" />
        </div>  
        <div class="cptp-input-wrapper">
            <button id="cptp-preview-city-text-button" class="cptp-button">Preview</button>
        </div>
    </div>

    <!-- Preview Image Modal -->
    <div id="cptp-preview-modal" class="cptp-modal">
        <span class="cptp-close">&times;</span>
        <div class="cptp-modal-content">
            <canvas id="cptp-canvas" width="500" height="500"></canvas>
        </div>
    </div>
    <?php
}
?>