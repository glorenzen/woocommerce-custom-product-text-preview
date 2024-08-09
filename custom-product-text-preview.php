<?php
/*
Plugin Name: Custom Product Text Preview
Description: Allows users to customize text on WooCommerce products and preview it before purchase.
Version: 1.0.0
Author: Greg Lorenzen
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CPTP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPTP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CPTP_PLUGIN_DIR . 'admin/class-cptp-admin.php';

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'cptp_activate_plugin');
register_deactivation_hook(__FILE__, 'cptp_deactivate_plugin');

function cptp_activate_plugin() {
    // Activation code here
}

function cptp_deactivate_plugin() {
    // Deactivation code here
}

// Initialize the plugin
function cptp_init_plugin() {
    if (is_admin()) {
        new CPTP_Admin();
    } else {
        // Include front-end functionality
    }
}
add_action('plugins_loaded', 'cptp_init_plugin');