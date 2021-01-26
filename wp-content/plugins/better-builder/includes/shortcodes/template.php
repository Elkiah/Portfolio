<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Template extends Better_Shortcode {

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_html__( 'Template', 'better' ),
				'shortcode' => 'better_template',
				'fields' => $this->get_fields()
			),
			$atts
		);
	}

}
