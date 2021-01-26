<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Text extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'fittext', BetterCore()->assets_url . 'shortcodes/text/FitText.js/jquery.fittext.min.js', array( 'jquery' ), '1.2' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Text', 'better' ),
				'shortcode' => 'better_text',
				'content' => 'html',
				'fields' => $this->get_fields()
			),
			$atts
		);
	}

}
