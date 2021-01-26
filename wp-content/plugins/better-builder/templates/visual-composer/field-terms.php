<?php
$output = '<select
		multiple 
        name="'
		. $settings['param_name']
		. '" id="'
		. $settings['param_name']
		. '" data-selected="'
		. $value
        . '" class="wpb_vc_param_value wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type'] . '">';
        
$categories = get_terms( 'category' );

if ( is_array( $categories ) ) {
    foreach ( $categories as $post_cat ) {
        $selected = '';
        $option_value_string = (string) $post_cat->slug;
		$value_string = (string) $value;
		if( ! is_array( $value ) ) {
			$param_value_arr = explode(',',$value);
		} else {
			$param_value_arr = $value;
		}

        if ( '' !== $value && in_array( $post_cat->slug, $param_value_arr ) ) {
            $selected = ' selected="selected"';
		}
		$output .= '<option class="' . $post_cat->slug . '" value="' . $post_cat->slug . '" ' . $selected . '>' . $post_cat->name . '</option>';
        
    }
}

$output .= '</select>';

echo $output;
