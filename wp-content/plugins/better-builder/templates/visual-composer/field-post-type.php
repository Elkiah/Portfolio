<?php
$output = '';
$output = '<select
        name="'
		. $settings['param_name']
		. '" id="'
        . $settings['param_name']
        . '" class="wpb_vc_param_value wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type'] . '">';
        
$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );

if ( is_array( $post_types ) ) {
    foreach ( $post_types as $post_type ) {
        $selected = '';
        $option_value_string = (string) $post_type;
        $value_string = (string) $value;
        if ( '' !== $value && $option_value_string === $value_string ) {
            $selected = ' selected="selected"';
		}
		if ( $post_type != 'attachment' && $post_type != 'product_variation' && $post_type != 'shop_coupon' ) {
			$type = get_post_type_object( $post_type );
			$output .= '<option class="' . $post_type . '" value="' . $post_type . '" ' . $selected . '>' . $type->label . '</option>';
		}
        
    }
}

$output .= '</select>';

echo $output;
