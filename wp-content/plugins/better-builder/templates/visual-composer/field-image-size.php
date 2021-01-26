<?php
$output = '';
$output = '<select
        name="'
        . $settings['param_name']
        . '" class="wpb_vc_param_value wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type'] . '">';

$wp_sizes = array_merge(
    array( '' => 'Select...' ),
    array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() )
);

if ( is_array( $wp_sizes ) ) {
    foreach ($wp_sizes as $wp_size) {
        $selected = '';
        $option_value_string = (string) $wp_size;
        $value_string = (string) $value;
        if ( '' !== $value && $option_value_string === $value_string ) {
            $selected = ' selected="selected"';
        }
        $output .= '<option class="' . $wp_size . '" value="' . $wp_size . '" ' . $selected . '>' . $wp_size . '</option>';
    }
}

$output .= '</select>';

echo $output;
