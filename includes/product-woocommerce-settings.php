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
add_action( 'woocommerce_variation_options', 'action_text_preview_checkbox', 10, 3);
add_action('woocommerce_product_after_variable_attributes', 'cptp_add_custom_text_preview_options_variation', 10, 3);
add_action('woocommerce_save_product_variation', 'cptp_save_custom_text_preview_options_variation', 10, 2);

function cptp_add_custom_text_preview_options_variation($loop, $variation_data, $variation) {
    $has_custom_text_preview = get_post_meta($variation->ID, '_has_custom_text_preview', true);
    if ($has_custom_text_preview === 'yes') {
        echo '<div class="cptp-toggle-wrapper">';
        echo '<button type="button" class="cptp-add-preview-button">' . __('Add Preview', 'woocommerce') . '</button>';
        echo '<div class="cptp-toggle">';
        woocommerce_wp_select(array(
            'id' => '_custom_text_preview_input_type',
            'label' => __('Input Type', 'woocommerce'),
            'options' => array(
                'text' => __('Text Input', 'woocommerce'),
                'dropdown' => __('Dropdown', 'woocommerce')
            )
        ));
        woocommerce_wp_text_input(array(
            'id' => '_custom_text_preview_label',
            'label' => __('Label', 'woocommerce')
        ));
        woocommerce_wp_checkbox(array(
            'id' => '_custom_text_preview_render_on_circle',
            'label' => __('Render on Circle', 'woocommerce')
        ));
        woocommerce_wp_checkbox(array(
            'id' => '_custom_text_preview_user_selected_font',
            'label' => __('User Selected Font', 'woocommerce')
        ));
        woocommerce_wp_text_input(array(
            'id' => '_custom_text_preview_image',
            'label' => __('Image', 'woocommerce'),
            'type' => 'file'
        ));
        echo '</div>';
        echo '</div>';
    }
}

function cptp_save_custom_text_preview_options_variation($variation_id, $i) {
    if ( ! empty( $_POST['_has_custom_text_preview'] ) && ! empty( $_POST['_has_custom_text_preview'][$i] ) ) {
        update_post_meta( $variation_id, '_has_custom_text_preview', 'yes' );
    } else {
        update_post_meta( $variation_id, '_has_custom_text_preview', 'no' ); 
    }   

    if (isset($_POST['_custom_text_preview_label'][$i])) {
        update_post_meta($variation_id, '_custom_text_preview_label', sanitize_text_field($_POST['_custom_text_preview_label'][$i]));
    }
    // Save more fields as needed
}

function cptp_add_custom_text_preview_toggle($post_id) {
    $has_custom_text_preview = get_post_meta($post_id, '_has_custom_text_preview', true);
    if ($has_custom_text_preview === 'yes') {
        echo '<div class="cptp-toggle-wrapper">';
        echo '<button type="button" class="cptp-add-preview-button">' . __('Add Preview', 'woocommerce') . '</button>';
        echo '<div class="cptp-toggle">';
        woocommerce_wp_select(array(
            'id' => '_custom_text_preview_input_type',
            'label' => __('Input Type', 'woocommerce'),
            'options' => array(
                'text' => __('Text Input', 'woocommerce'),
                'dropdown' => __('Dropdown', 'woocommerce')
            )
        ));
        woocommerce_wp_text_input(array(
            'id' => '_custom_text_preview_label',
            'label' => __('Label', 'woocommerce')
        ));
        woocommerce_wp_checkbox(array(
            'id' => '_custom_text_preview_render_on_circle',
            'label' => __('Render on Circle', 'woocommerce')
        ));
        woocommerce_wp_checkbox(array(
            'id' => '_custom_text_preview_user_selected_font',
            'label' => __('User Selected Font', 'woocommerce')
        ));
        woocommerce_wp_text_input(array(
            'id' => '_custom_text_preview_image',
            'label' => __('Image', 'woocommerce'),
            'type' => 'file'
        ));
        echo '</div>';
        echo '</div>';
    }
}
