<?php
// Check if the product has custom text enabled
if (get_field('has_custom_text')) {
    ?>
    <div class="cptp-product-custom-text" style="display: none;">
        <div class="cptp-input-wrapper">
            <label for="cptp-custom_text" class="cptp-form-label">Custom Text:</label>
            <input type="text" id="cptp-custom-text" name="cptp-custom_text" class="cptp-form-control" value="" />
        </div>  
        <div class="cptp-input-wrapper">
            <button id="cptp-preview-text-button" class="cptp-button">Preview Text</button>
        </div>
    </div>
    <?php
}
?>