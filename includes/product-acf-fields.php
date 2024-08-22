<?php
if( function_exists('acf_add_local_field_group') ):

require_once CPTP_PLUGIN_DIR . 'utils/utils.php';

add_action('acf/render_field/name=font_family', 'acf_render_font_family_dropdown');

function acf_render_font_family_dropdown($field) {
    $selected_font = $field['value'];
    render_font_family_dropdown($field['name'], $selected_font);
}

acf_add_local_field_group(array(
    'key' => 'group_product_custom_text',
    'title' => 'Product Custom Text Settings',
    'fields' => array(
        array(
            'key' => 'field_has_custom_text',
            'label' => 'Has Custom Text',
            'name' => 'has_custom_text',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => 'Yes',
            'ui_off_text' => 'No',
        ),
        array(
            'key' => 'field_font_size_group',
            'label' => 'Font Size',
            'name' => 'font_size_group',
            'type' => 'group',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_has_custom_text',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'sub_fields' => array(
                array(
                    'key' => 'field_override_font_size',
                    'label' => 'Override Font Size',
                    'name' => 'override_font_size',
                    'type' => 'true_false',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_font_size',
                    'label' => 'Font Size',
                    'name' => 'font_size',
                    'type' => 'number',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_override_font_size',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_font_color_group',
            'label' => 'Font Color',
            'name' => 'font_color_group',
            'type' => 'group',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_has_custom_text',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'sub_fields' => array(
                array(
                    'key' => 'field_override_font_color',
                    'label' => 'Override Font Color',
                    'name' => 'override_font_color',
                    'type' => 'true_false',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_font_color',
                    'label' => 'Font Color',
                    'name' => 'font_color',
                    'type' => 'color_picker',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_override_font_color',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'product',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
));

endif;