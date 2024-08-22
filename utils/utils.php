<?php
function render_font_family_dropdown($name, $selected_font) {
    $fonts = array(
        'Open Sans', 'Graduate', 'Monoton', 'Rye', 'UnifrakturMaguntia', 'Vast Shadow'
    );
    echo '<select name="' . esc_attr($name) . '">';
    foreach ($fonts as $font) {
        $selected = ($selected_font === $font) ? 'selected' : '';
        echo "<option value='" . esc_attr($font) . "' $selected>" . esc_html($font) . "</option>";
    }
    echo '</select>';
}