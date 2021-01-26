<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Lottie_Animation extends Better_Shortcode {

	/* Resources
	https://airbnb.design/modern-pictograms-for-lottie/
	https://www.lottiefiles.com/
	http://airbnb.io/lottie/after-effects/artwork-to-lottie-walkthrough.html
	https://github.com/bodymovin/bodymovin
	 */

	public function register_assets() {
		wp_register_script( 'bodymovin', BetterCore()->assets_url . 'shortcodes/lottie-animation/bodymovin.min.js', array( 'jquery' ), '4.10.3' );
		wp_enqueue_script( 'bodymovin' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Lottie Animation', 'better' ),
				'shortcode' => 'better_lottie_animation',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
