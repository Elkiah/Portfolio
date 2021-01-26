<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Gif_Player extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'freezeframe', BetterCore()->assets_url . 'shortcodes/gif-player/freezeframe.min.js', array( 'jquery' ), '3.0.8' );
		wp_register_style( 'freezeframe', BetterCore()->assets_url . 'shortcodes/gif-player/freezeframe.min.css', array( ), '3.0.8' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'GIF Player', 'better' ),
				'shortcode' => 'better_gif_player',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
