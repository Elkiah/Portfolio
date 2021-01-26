<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Image_Zoom extends Better_Shortcode {

	public function register_assets() {		
		wp_register_script( 'medium-zoom', BetterCore()->assets_url . 'shortcodes/image-zoom/medium-zoom.min.js', array( 'jquery' ), '0.2.0' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_attr__( 'Image Zoom', 'better' ),
				'shortcode' => 'better_image_zoom',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}