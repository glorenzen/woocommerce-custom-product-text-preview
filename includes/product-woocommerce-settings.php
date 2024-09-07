<?php
function action_text_preview_checkbox( $loop, $variation_data, $variation ) {
    $is_checked = get_post_meta( $variation->ID, '_has_custom_text_preview', true );

    if ( $is_checked == 'yes' ) {
        $is_checked = 'checked';
    } else {
        $is_checked = '';     
    }

    ?>
    <label class="tips" data-tip="<?php esc_attr_e( 'Display a custom text preview for this variation', 'woocommerce' ); ?>">
        <?php esc_html_e( 'Custom Text Preview', 'woocommerce' ); ?>
        <input type="checkbox" class="checkbox variable_checkbox" name="_has_custom_text_preview[<?php echo esc_attr( $loop ); ?>]"<?php echo $is_checked; ?>/>
    </label>
    <?php
}
add_action('woocommerce_variation_options', 'action_text_preview_checkbox', 10, 3);
add_action('woocommerce_product_after_variable_attributes', 'cptp_add_custom_text_preview_options_variation', 10, 3);
add_action('woocommerce_save_product_variation', 'cptp_save_custom_text_preview_options_variation', 10, 2);

function cptp_add_custom_text_preview_options_variation($loop, $variation_data, $variation) {
    $has_custom_text_preview = get_post_meta($variation->ID, '_has_custom_text_preview', true);
    if ($has_custom_text_preview === 'yes') {
        $preview_options = get_post_meta($variation->ID, '_custom_text_preview_options', true);
        if (empty($preview_options)) {
            $preview_options = array(array());
        }

        echo '<div class="cptp-preview-options-wrapper">';
        foreach ($preview_options as $index => $option) {
            echo '<div class="cptp-preview-option">';
            echo '<h4>' . __('Preview Option', 'woocommerce') . ' ' . ($index + 1) . '</h4>';
            woocommerce_wp_select(array(
                'id' => '_custom_text_preview_input_type[' . $loop . '][' . $index . ']',
                'label' => __('Input Type', 'woocommerce'),
                'options' => array(
                    'text' => __('Text Input', 'woocommerce'),
                    'dropdown' => __('Dropdown', 'woocommerce')
                ),
                'value' => isset($option['input_type']) ? $option['input_type'] : ''
            ));
            woocommerce_wp_text_input(array(
                'id' => '_custom_text_preview_label[' . $loop . '][' . $index . ']',
                'label' => __('Label', 'woocommerce'),
                'value' => isset($option['label']) ? $option['label'] : ''
            ));
            woocommerce_wp_text_input(array(
                'id' => '_custom_text_preview_dropdown_values[' . $loop . '][' . $index . ']',
                'label' => __('Dropdown Values (comma separated)', 'woocommerce'),
                'value' => isset($option['dropdown_values']) ? $option['dropdown_values'] : '',
                'class' => 'cptp-dropdown-values',
            ));
            woocommerce_wp_checkbox(array(
                'id' => '_custom_text_preview_render_on_circle[' . $loop . '][' . $index . ']',
                'label' => __('Render on Circle', 'woocommerce'),
                'value' => isset($option['render_on_circle']) ? $option['render_on_circle'] : ''
            ));
            woocommerce_wp_checkbox(array(
                'id' => '_custom_text_preview_user_selected_font[' . $loop . '][' . $index . ']',
                'label' => __('User Selected Font', 'woocommerce'),
                'value' => isset($option['user_selected_font']) ? $option['user_selected_font'] : ''
            ));
            ?>
            <p class="form-field">
                <label for="_custom_text_preview_image_<?php echo $loop; ?>_<?php echo $index; ?>"><?php _e('Image', 'woocommerce'); ?></label>
                <input type="hidden" id="_custom_text_preview_image_<?php echo $loop; ?>_<?php echo $index; ?>" name="_custom_text_preview_image[<?php echo $loop; ?>][<?php echo $index; ?>]" value="<?php echo isset($option['image']) ? esc_attr($option['image']) : ''; ?>" />
                <button type="button" class="button cptp-upload-image-button" data-target="#_custom_text_preview_image_<?php echo $loop; ?>_<?php echo $index; ?>"><?php _e('Select Image', 'woocommerce'); ?></button>
                <span class="cptp-image-preview"><?php echo isset($option['image']) ? '<img src="' . esc_url($option['image']) . '" style="max-width:100px;" />' : ''; ?></span>
            </p>
            <hr>
            <?php
            echo '</div>';
        }
        echo '</div>';
        echo '<button type="button" class="button cptp-add-preview-button">' . __('Add Preview', 'woocommerce') . '</button>';
    }
}

function cptp_save_custom_text_preview_options_variation($variation_id, $i) {
    if ( ! empty( $_POST['_has_custom_text_preview'] ) && ! empty( $_POST['_has_custom_text_preview'][$i] ) ) {
        update_post_meta( $variation_id, '_has_custom_text_preview', 'yes' );
    } else {
        update_post_meta( $variation_id, '_has_custom_text_preview', 'no' ); 
    }   

    $preview_options = array();
    if (isset($_POST['_custom_text_preview_input_type'][$i])) {
        foreach ($_POST['_custom_text_preview_input_type'][$i] as $index => $input_type) {
            $preview_options[$index] = array(
                'input_type' => sanitize_text_field($input_type),
                'label' => sanitize_text_field($_POST['_custom_text_preview_label'][$i][$index]),
                'render_on_circle' => isset($_POST['_custom_text_preview_render_on_circle'][$i][$index]) ? 'yes' : 'no',
                'user_selected_font' => isset($_POST['_custom_text_preview_user_selected_font'][$i][$index]) ? 'yes' : 'no',
                'image' => sanitize_text_field($_POST['_custom_text_preview_image'][$i][$index])
            );
        }
    }
    update_post_meta($variation_id, '_custom_text_preview_options', $preview_options);
}
