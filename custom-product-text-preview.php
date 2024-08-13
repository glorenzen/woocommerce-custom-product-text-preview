<?php
/*
Plugin Name: Custom Product Text Preview
Description: Allows users to customize text on WooCommerce products and preview it before purchase.
Version: 1.0.0
Author: Greg Lorenzen
Requires Plugins: advanced-custom-fields
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
require_once CPTP_PLUGIN_DIR . 'public/class-cptp-public.php';
require_once CPTP_PLUGIN_DIR . 'includes/product-acf-fields.php';

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'cptp_activate_plugin');
register_deactivation_hook(__FILE__, 'cptp_deactivate_plugin');

function cptp_activate_plugin() {
    // Activation code here
}

function cptp_deactivate_plugin() {
    // Deactivation code here
}

function enqueue_fabric() {
    wp_register_script('fabric', 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js', null, null, true);
    wp_enqueue_script('fabric');
}

function enqueue_admin_scripts() {
    enqueue_fabric();
}

// Enqueue scripts
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

// Initialize the plugin
function cptp_init_plugin() {
    if (is_admin()) {
        new CPTP_Admin();
    } else {
        new CPTP_Public();
    }
}
add_action('plugins_loaded', 'cptp_init_plugin');