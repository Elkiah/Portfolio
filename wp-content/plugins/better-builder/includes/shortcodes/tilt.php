<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Tilt extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'vanilla.tilt', BetterCore()->assets_url . 'shortcodes/tilt/vanilla-tilt.min.js', array( 'jquery' ), '1.4.1', true );
		wp_register_script( 'better.tilt', BetterCore()->assets_url . 'shortcodes/tilt/better-tilt.js', array( 'jquery' ), '1.0.0', true );
		wp_register_style( 'better.tilt', BetterCore()->assets_url . 'shortcodes/tilt/better-tilt.css' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Tilt', 'better' ),
				'shortcode' => 'better_tilt',
				'content' => 'html',
				'fields' => $this->get_fields()
			),
			$atts
		);
	}

}
