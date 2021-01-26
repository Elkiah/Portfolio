<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Image_3d extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'interactive_3d', BetterCore()->assets_url . 'shortcodes/image-3d/jquery.interactive_3d.min.js', array( 'jquery' ), '1.1' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Image 3D', 'better' ),
				'shortcode' => 'better_image_3d',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
