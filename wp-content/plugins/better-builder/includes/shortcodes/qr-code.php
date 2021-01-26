<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Qr_Code extends Better_Shortcode {

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_attr__( 'QR Code', 'better' ),
				'shortcode' => 'better_qr_code',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
