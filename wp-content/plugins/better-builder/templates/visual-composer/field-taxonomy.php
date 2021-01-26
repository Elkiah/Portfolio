<?php
$output = '<select
        name="'
		. $settings['param_name']
		. '" id="'
		. $settings['param_name']
		. '" data-selected="'
		. $value
        . '" class="wpb_vc_param_value wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type'] . '">';
        
$taxonomy_names = get_object_taxonomies( 'post' );

if ( is_array( $taxonomy_names ) ) {
    foreach ( $taxonomy_names as $taxonomy_name ) {
        $selected = '';
        $option_value_string = (string) $taxonomy_name;
        $value_string = (string) $value;
        if ( '' !== $value && $option_value_string === $value_string ) {
            $selected = ' selected="selected"';
		}
		$tax_obj = get_taxonomy( $taxonomy_name );
		$output .= '<option class="' . $taxonomy_name . '" value="' . $taxonomy_name . '" ' . $selected . '>' . $tax_obj->label . '</option>';
        
    }
}

$output .= '</select>';

echo $output;
