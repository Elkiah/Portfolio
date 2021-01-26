<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Filler extends Better_Shortcode {

	public function register_assets() {
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Filler', 'better' ),
				'shortcode' => 'better_filler',
				'fields' => $this->get_fields(),
			),
			$atts
		 );
	}

}
