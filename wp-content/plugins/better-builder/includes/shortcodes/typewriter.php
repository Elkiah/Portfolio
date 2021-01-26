<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Typewriter extends Better_Shortcode {
	public function register_assets() {
		wp_register_script( 'typed', BetterCore()->assets_url . 'shortcodes/typewriter/typed.js', array( ), '2.0.9', true );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_attr__( 'Typewriter', 'better' ),
				'shortcode' => 'better_typewriter',
				'content' => 'textarea',
				'content_description' => __('Enter one statement per line.', 'better'),
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}