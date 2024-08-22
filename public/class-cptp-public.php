<?php
class CPTP_Public {
    public function __construct() {
        // Enqueue public scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));

        // Render custom text input on single product page
        add_action('woocommerce_before_add_to_cart_button', array($this, 'render_single_product_display'), 25);

        // WooCommerce hooks for custom text functionality
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_custom_text_to_cart_item'), 10, 2);
        add_filter('woocommerce_get_item_data', array($this, 'display_custom_text_in_cart'), 10, 2);
    }

    public function enqueue_public_scripts() {
        wp_enqueue_style('cptp-public', plugin_dir_url(__FILE__) . 'css/cptp-public.css', array(), date("h:i:s"));
        wp_enqueue_script('cptp-public-js', plugin_dir_url(__FILE__) . 'js/cptp-public.js', array('jquery'), date("h:i:s"), true);

        $acf_fields = array(
            'font_size_group' => get_field('font_size_group'),
            'font_color_group' => get_field('font_color_group'),
            'field_name_text_preview_image' => get_field('field_name_text_preview_image'),
        );
        $logo_font_mapping = array(
            'default' => 'Vast Shadow',
            'Carpenters' => 'Monoton',
            'Ancient Order of Divers' => 'Rye',
        );
        wp_localize_script('cptp-public-js', 'cptp_values', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID(),
            'acf_fields' => $acf_fields,
            'logo_font_mapping' => $logo_font_mapping,
        ));
    }

    public function render_single_product_display() {
        include plugin_dir_path(__FILE__) . 'partials/cptp-single-product-display.php';
    }

    public function add_custom_text_to_cart_item($cart_item_data, $product_id) {
        if (isset($_POST['cptp_custom_text'])) {
            $cart_item_data['cptp_custom_text'] = sanitize_text_field($_POST['cptp_custom_text']);
        }
        return $cart_item_data;
    }

    public function display_custom_text_in_cart($item_data, $cart_item) {
        if (isset($cart_item['cptp_custom_text'])) {
            $item_data[] = array(
                'key' => __('Custom Text', 'cptp'),
                'value' => wc_clean($cart_item['cptp_custom_text']),
            );
        }
        return $item_data;
    }
}