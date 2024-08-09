<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CPTP_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Custom Product Text Preview Settings',
            'Text Preview Settings',
            'manage_options',
            'cptp-settings',
            array($this, 'create_admin_page'),
            'dashicons-admin-generic'
        );
    }

    public function register_settings() {
        register_setting('cptp_settings_group', 'cptp_x_coordinate');
        register_setting('cptp_settings_group', 'cptp_y_coordinate');
        register_setting('cptp_settings_group', 'cptp_preview_image');

        add_settings_section(
            'cptp_settings_section',
            'Global Customization Settings',
            null,
            'cptp-settings'
        );

        add_settings_field(
            'cptp_x_coordinate',
            'X-Coordinate',
            array($this, 'x_coordinate_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_y_coordinate',
            'Y-Coordinate',
            array($this, 'y_coordinate_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_preview_image',
            'Preview Image',
            array($this, 'preview_image_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );
    }

    public function create_admin_page() {
        include_once CPTP_PLUGIN_DIR . 'admin/partials/cptp-admin-display.php';
    }

    public function x_coordinate_callback() {
        $x_coordinate = get_option('cptp_x_coordinate');
        echo '<input type="number" name="cptp_x_coordinate" value="' . esc_attr($x_coordinate) . '" />';
    }

    public function y_coordinate_callback() {
        $y_coordinate = get_option('cptp_y_coordinate');
        echo '<input type="number" name="cptp_y_coordinate" value="' . esc_attr($y_coordinate) . '" />';
    }

    public function preview_image_callback() {
        $preview_image = get_option('cptp_preview_image');
        echo '<input type="text" name="cptp_preview_image" value="' . esc_attr($preview_image) . '" />';
        echo '<button class="button cptp-upload-button">Add Preview Image</button>';
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_media();
        wp_enqueue_script('cptp-admin-js', CPTP_PLUGIN_URL . 'admin/js/cptp-admin.js', array('jquery'), null, true);
    }
}