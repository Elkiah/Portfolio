<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Button extends Better_Shortcode {

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_attr__( 'Button', 'better' ),
				'shortcode' => 'better_button',
				'content' => 'textarea',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
