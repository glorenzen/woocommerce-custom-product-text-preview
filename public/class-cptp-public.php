<?php
class CPTP_Public {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
        add_action('woocommerce_before_add_to_cart_button', array($this, 'render_single_product_display'), 25);
    }

    public function enqueue_public_scripts() {
        wp_enqueue_style('cptp-public-css', plugin_dir_url(__FILE__) . 'css/cptp-public.css');
        wp_enqueue_script('cptp-public-js', plugin_dir_url(__FILE__) . 'js/cptp-public.js', array('jquery'), date("h:i:s"), true);
        wp_localize_script('cptp-public-js', 'cptp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID(),
        ));
    }

    public function render_single_product_display() {
        include plugin_dir_path(__FILE__) . 'partials/cptp-single-product-display.php';
    }
}