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
        add_filter('woocommerce_order_item_display_meta_key', array($this, 'display_custom_text_in_order_meta'), 10, 3);
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

    public function render_preview_modal() {
        include plugin_dir_path(__FILE__) . 'partials/cptp-preview-modal.php';
    }

    public function add_custom_text_to_cart_item($cart_item_data, $product_id) {
        if (isset($_POST['cptp_custom_name_text'])) {
            $cart_item_data['cptp_custom_name_text'] = sanitize_text_field($_POST['cptp_custom_name_text']);
        }
        if (isset($_POST['cptp_custom_city_text'])) {
            $cart_item_data['cptp_custom_city_text'] = sanitize_text_field($_POST['cptp_custom_city_text']);
        }
        if (isset($_POST['cptp_selected_name_font'])) {
            $cart_item_data['cptp_selected_name_font'] = sanitize_text_field($_POST['cptp_selected_name_font']);
        }
        return $cart_item_data;
    }

    public function display_custom_text_in_cart($item_data, $cart_item) {
        if (isset($cart_item['cptp_custom_name_text'])) {
            $item_data[] = array(
                'key' => __('Custom Name Text', 'cptp'),
                'value' => wc_clean($cart_item['cptp_custom_name_text']),
            );
        }
        if (isset($cart_item['cptp_custom_city_text'])) {
            $item_data[] = array(
                'key' => __('Custom Logo Text', 'cptp'),
                'value' => wc_clean($cart_item['cptp_custom_city_text']),
            );
        }
        if (isset($cart_item['cptp_selected_name_font'])) {
            $item_data[] = array(
                'key' => __('Name Font', 'cptp'),
                'value' => wc_clean($cart_item['cptp_selected_name_font']),
            );
        }
        return $item_data;
    }
    public function save_custom_text_to_order_items($item, $cart_item_key, $values, $order) {
        if (!empty($values['cptp_selected_name_font'])) {
            $item->add_meta_data(__('Selected Name Font','cptp'), $values['cptp_selected_name_font']);
        }
        
        if (!empty($values['cptp_custom_name_text'])) {
            $item->add_meta_data(__('Custom Name Text','cptp'), sanitize_text_field($values['cptp_custom_name_text']));
        }

        if (!empty($values['cptp_custom_city_text'])) {
            $item->add_meta_data(__('Custom City Text','cptp'), sanitize_text_field($values['cptp_custom_city_text']));
        }
    }

    public function display_custom_text_in_order_meta($display_key, $meta, $item) {
        if ($meta->key === __('Custom Name Text','cptp')) {
            $display_key = 'Custom Name Text';
        }
        else if ($meta->key === __('Custom City Text','cptp')) {
            $display_key = 'Custom City Text';
        }
        else if ($meta->key === __('Selected Name Font','cptp')) {
            $display_key = 'Selected Name Font';
        }
        return $display_key;
    }
}