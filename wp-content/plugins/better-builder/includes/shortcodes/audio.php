<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Audio extends Better_Shortcode {

	public function register_assets() {
		global $pagenow, $typenow;

		wp_register_script( 'amplitude', BetterCore()->assets_url . 'shortcodes/audio/amplitude.min.js', NULL, '3.2.3' );
		wp_register_script( 'foundation', BetterCore()->assets_url . 'shortcodes/audio/foundation.min.js', NULL, '3.2.3' );
		wp_register_style( 'amplitude-blue-playlist', BetterCore()->assets_url . 'shortcodes/audio/blue-playlist/css/app.css', array(), '3.2.3' );
		wp_register_style( 'amplitude-flat-black', BetterCore()->assets_url . 'shortcodes/audio/flat-black/css/app.css', array(), '3.2.3' );
		wp_register_style( 'amplitude-multiple-songs', BetterCore()->assets_url . 'shortcodes/audio/multiple-songs/css/app.css', array(), '3.2.3' );
		wp_register_style( 'amplitude-single-song', BetterCore()->assets_url . 'shortcodes/audio/single-song/css/app.css', array(), '3.2.3' );
		wp_register_style( 'foundation', BetterCore()->assets_url . 'shortcodes/audio/foundation.min.css', array(), '3.2.3' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Audio', 'better' ),
				'shortcode' => 'better_audio',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
