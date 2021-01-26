<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Stock_Image extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'better.stock-image', BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.js', array( 'jquery', 'underscore' ), '1.0.0', true );
		wp_register_style( 'better.stock-image', BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.css' );

		wp_enqueue_style( 'better.stock-image' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Stock Image', 'better' ),
				'shortcode' => 'better_stock_image',
				'fs_admin_enqueue_js' => BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.js',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
