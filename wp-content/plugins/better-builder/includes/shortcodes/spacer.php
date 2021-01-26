<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Spacer extends Better_Shortcode {

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Spacer', 'better' ),
				'shortcode' => 'better_spacer',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
