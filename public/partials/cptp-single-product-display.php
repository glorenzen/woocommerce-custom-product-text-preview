<?php
// Set font options
$font_options = array('Graduate', 'Monoton', 'Vast Shadow', 'Rye', 'UnifrakturMaguntia');

// Get the custom text max length
$custom_text_max_length = get_option('cptp_custom_text_max_length', 20);

// Retrieve the custom text preview options for each variation
global $product;
$variations = $product->get_available_variations();
$variation_preview_options = array();

foreach ($variations as $variation) {
    $variation_id = $variation['variation_id'];
    $has_custom_text_preview = get_post_meta($variation_id, '_has_custom_text_preview', true);
    if ($has_custom_text_preview === 'yes') {
        $preview_options = get_post_meta($variation_id, '_custom_text_preview_options', true);
        $preview_title = get_post_meta($variation_id, '_custom_text_preview_title', true);
        if (!empty($preview_options)) {
            $variation_preview_options[$variation_id] = array(
                'title' => $preview_title,
                'options' => $preview_options
            );
        }
    }
}
?>
<div class="cptp-product-custom-text" style="display: none;">
    <div id="cptp-preview-options-container"></div>
</div>
<script type="text/javascript">
    var variationPreviewOptions = <?php echo json_encode($variation_preview_options); ?>;
    var fontOptions = <?php echo json_encode($font_options); ?>;
    var customTextMaxLength = <?php echo json_encode($custom_text_max_length); ?>;
</script>