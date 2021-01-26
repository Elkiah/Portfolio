<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Image_Compare extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'jquery.event.move', BetterCore()->assets_url . 'shortcodes/image-compare/twentytwenty/js/jquery.event.move.js', array( 'jquery' ), '1.3.6' );
		wp_register_script( 'twentytwenty', BetterCore()->assets_url . 'shortcodes/image-compare/twentytwenty/js/jquery.twentytwenty.js', array( 'jquery', 'jquery.event.move'  ), '20140519' );
		wp_register_style( 'twentytwenty', BetterCore()->assets_url . 'shortcodes/image-compare/twentytwenty/css/twentytwenty.min.css', array( ), '20140519' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Image Compare', 'better' ),
				'shortcode' => 'better_image_compare',
				'fields' => $this->get_fields()
			),
			$atts
		);
	}

}
