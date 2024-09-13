<?php
class CPTP_Public_API {
    public function __construct() {
        // Hook to handle AJAX requests
        add_action('wp_ajax_get_featured_image', array($this, 'get_featured_image'));
        add_action('wp_ajax_nopriv_get_featured_image', array($this, 'get_featured_image'));
        
        // Hook to register REST API routes
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    // Function to handle AJAX request for getting the featured image
    public function get_featured_image() {
        $variation_id = intval($_POST['variation_id']);
        $featured_image_url = wp_get_attachment_url(get_post_thumbnail_id($variation_id));

        if ($featured_image_url) {
            wp_send_json_success($featured_image_url);
        } else {
            wp_send_json_error('No featured image found.');
        }

        wp_die();
    }

    // Function to register REST API routes
    public function register_rest_routes() {
        register_rest_route('cptp/v1', '/settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_settings'),
            'permission_callback' => '__return_true'
        ));
    }

    // Function to get the plugin settings via REST API
    public function get_settings() {
        $settings = array(
            'x_coordinate' => get_option('cptp_x_coordinate'),
            'y_coordinate' => get_option('cptp_y_coordinate'),
            'circle_width' => get_option('cptp_circle_width'),
            'custom_text_max_length' => get_option('cptp_custom_text_max_length'),
        );

        return new WP_REST_Response($settings, 200);
    }
}

// Instantiate the class
$cptp_public_api = new CPTP_Public_API();