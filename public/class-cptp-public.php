<?php
class CPTP_Public {
    public function __construct() {
        // Enqueue public scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));

        // Render custom text input on single product page
        add_action('woocommerce_before_add_to_cart_button', array($this, 'render_single_product_display'), 25);

        // Render preview modal in footer
        add_action('wp_footer', array($this, 'render_preview_modal'));

        // WooCommerce hooks for custom text functionality
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_custom_text_to_cart_item'), 10, 2);
        add_filter('woocommerce_get_item_data', array($this, 'display_custom_text_in_cart'), 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_custom_text_to_order_items'), 10, 4);
    }

    public function enqueue_public_scripts() {
        wp_enqueue_style('cptp-public', plugin_dir_url(__FILE__) . 'css/cptp-public.css', array(), date("h:i:s"));
        wp_enqueue_script('cptp-public-js', plugin_dir_url(__FILE__) . 'js/cptp-public.js', array('jquery'), date("h:i:s"), true);

        $logo_font_mapping = array(
            'default' => 'Vast Shadow',
            'Carpenters' => 'Monoton',
            'Ancient Order of Divers' => 'Rye',
        );
        wp_localize_script('cptp-public-js', 'cptp_values', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID(),
            'logo_font_mapping' => $logo_font_mapping,
        ));
    }

    public function render_single_product_display() {
        include plugin_dir_path(__FILE__) . 'partials/cptp-single-product-display.php';
    }

    public function render_preview_modal() {
        include plugin_dir_path(__FILE__) . 'partials/cptp-preview-modal.php';
    }

    public function add_custom_text_to_cart_item($cart_item_data, $product_id) {
        if (isset($_POST['preview_options_data'])) {
            $preview_options_data = json_decode(stripslashes($_POST['preview_options_data']), true);
            if (is_array($preview_options_data)) {
                foreach ($preview_options_data as $option) {
                    $name = sanitize_text_field($option['name']);
                    $label = sanitize_text_field($option['label']);
                    $value = sanitize_text_field($option['value']);
                    $cart_item_data['preview_options'][$name] = array(
                        'label' => $label,
                        'value' => $value
                    );
                }
            }
        }

        return $cart_item_data;
    }

    public function display_custom_text_in_cart($item_data, $cart_item) {
        if (isset($cart_item['preview_options']) && is_array($cart_item['preview_options'])) {
            foreach ($cart_item['preview_options'] as $name => $option) {
                $item_data[] = array(
                    'key' => wc_clean($option['label']),
                    'value' => wc_clean($option['value']),
                );
            }
        }
        return $item_data;
    }
    public function save_custom_text_to_order_items($item, $cart_item_key, $values, $order) {
        if (isset($values['preview_options']) && is_array($values['preview_options'])) {
            foreach ($values['preview_options'] as $name => $option) {
                $item->add_meta_data(wc_clean($option['label']), wc_clean($option['value']));
            }
        }
    }
}