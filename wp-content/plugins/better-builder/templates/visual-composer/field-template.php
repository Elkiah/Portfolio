<?php
$output = '';
$output = '<select
        name="'
        . $settings['param_name']
        . '" class="wpb_vc_param_value wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type'] . '">';
        
$templates = better_locate_available_plugin_templates( '/template/' );

if ( is_array( $templates ) ) {
    foreach ($templates as $key => $template) {
        $selected = '';
        $option_value_string = (string) $key;
        $value_string = (string) $value;
        if ( '' !== $value && $option_value_string === $value_string ) {
            $selected = ' selected="selected"';
        }
        $output .= '<option class="' . $key . '" value="' . $key . '" ' . $selected . '>' . $template . '</option>';
    }
}

$output .= '</select>';

echo $output;
