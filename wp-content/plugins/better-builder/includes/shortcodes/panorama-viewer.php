<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Panorama_Viewer extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'panorama-viewer', BetterCore()->assets_url . 'shortcodes/panorama-viewer/jquery.panorama_viewer.min.js', array( 'jquery' ), '1' );
		wp_register_style( 'panorama-viewer', BetterCore()->assets_url . 'shortcodes/panorama-viewer/panorama_viewer.min.css', array( ), '1' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_attr__( 'Panorama Viewer', 'better' ),
				'shortcode' => 'better_panorama_viewer',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}