<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once CPTP_PLUGIN_DIR . 'utils/utils.php';

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
        register_setting('cptp_settings_group', 'cptp_circle_width');
        register_setting('cptp_settings_group', 'cptp_preview_image');
        register_setting('cptp_settings_group', 'cptp_custom_text');
        register_setting('cptp_settings_group', 'cptp_custom_text_max_length');
        register_setting('cptp_settings_group', 'cptp_font_size');
        register_setting('cptp_settings_group', 'cptp_font_family');
        register_setting('cptp_settings_group', 'cptp_font_color');
        register_setting('cptp_settings_group', 'cptp_circle_color');
    
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
            'cptp_circle_width',
            'Circle Width',
            array($this, 'circle_width_callback'),
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
    
        add_settings_field(
            'cptp_custom_text',
            'Custom Text',
            array($this, 'custom_text_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_custom_text_max_length',
            'Custom Text Max Length',
            array($this, 'cptp_custom_text_max_length_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_font_size',
            'Font Size',
            array($this, 'font_size_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_font_family',
            'Font Family',
            array($this, 'font_family_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_font_color',
            'Font Color',
            array($this, 'font_color_callback'),
            'cptp-settings',
            'cptp_settings_section'
        );

        add_settings_field(
            'cptp_circle_color',
            'Circle Color',
            array($this, 'circle_color_callback'),
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

    public function circle_width_callback() {
        $circle_width = get_option('cptp_circle_width');
        echo '<input type="number" name="cptp_circle_width" value="' . esc_attr($circle_width) . '" />';
    }

    public function preview_image_callback() {
        $preview_image = get_option('cptp_preview_image');
        echo '<input type="text" name="cptp_preview_image" value="' . esc_attr($preview_image) . '" />';
        echo '<button class="button cptp-upload-button">Add Preview Image</button>';
    }

    public function custom_text_callback() {
        $custom_text = get_option('cptp_custom_text');
        echo '<input type="text" name="cptp_custom_text" value="' . esc_attr($custom_text) . '" />';
        echo '<p class="description">This text is for preview purposes only and will not be shown on the front end.</p>';
    }

    public function cptp_custom_text_max_length_callback() {
        $value = get_option('cptp_custom_text_max_length', 20); // Default to 20 if not set
        echo '<input type="number" id="cptp_custom_text_max_length" name="cptp_custom_text_max_length" value="' . esc_attr($value) . '" />';
    }

    public function font_size_callback() {
        $value = get_option('cptp_font_size', '');
        echo '<input type="number" id="cptp_font_size" name="cptp_font_size" value="' . esc_attr($value) . '" />';
    }

    public function font_family_callback() {
        $selected_font = get_option('cptp_font_family', 'Open Sans');
        render_font_family_dropdown('cptp_font_family', $selected_font);
    }

    public function font_color_callback() {
        $value = get_option('cptp_font_color', '');
        echo '<input type="color" id="cptp_font_color" name="cptp_font_color" value="' . esc_attr($value) . '" />';
    }

    public function circle_color_callback() {
        $value = get_option('cptp_circle_color', '');
        echo '<input type="color" id="cptp_circle_color" name="cptp_circle_color" value="' . esc_attr($value) . '" />';
        echo '<p class="description">This color is for preview purposes only and will not be shown on the front end.</p>';
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_media();
        wp_enqueue_style('cptp-admin', CPTP_PLUGIN_URL . 'admin/css/cptp-admin.css');
        wp_enqueue_script('cptp-admin', CPTP_PLUGIN_URL . 'admin/js/cptp-admin.js', array('jquery'), date("h:i:s"), true);
    }
}