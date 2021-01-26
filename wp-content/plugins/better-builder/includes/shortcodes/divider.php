<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Divider extends Better_Shortcode {

	public function register_assets() {
		wp_enqueue_style( 'better.divider', BetterCore()->assets_url . 'shortcodes/divider/divider.css' );

		$separators = $this->separator_types();
		foreach ( $separators as $key => $value ) {
			if ( ! empty( $key ) ) {
				$style_name = 'better.' . $key;
				wp_register_style( 'better.' . $key, BetterCore()->assets_url . 'shortcodes/divider/css/' . $key . '.min.css' );
				wp_enqueue_style( 'better.' . $key );
			}
		}
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_html__( 'Divider', 'better' ),
				'shortcode' => 'better_divider',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

	public static function separator_types() {
		return array(
			'' => '',
			'boxes' => esc_html__( 'Boxes', 'better' ),
			'circle-big' => esc_html__( 'Circle Big', 'better' ),
			'circle-edge' => esc_html__( 'Circle Edge', 'better' ),
			'circle' => esc_html__( 'Circle', 'better' ),
			'clouds' => esc_html__( 'Clouds', 'better' ),
			'curve-big' => esc_html__( 'Curve Big', 'better' ),
			'curve-left' => esc_html__( 'Curve Left', 'better' ), 
			'curve-right' => esc_html__( 'Curve Right', 'better' ),
			'diagonal-left' => esc_html__( 'Diagonal Left', 'better' ),
			'diagonal-right' => esc_html__( 'Diagonal Right', 'better' ),
			'diamond-tripple' => esc_html__( 'Diamond - Tripple', 'better' ),
			'diamond' => esc_html__( 'Diamond', 'better' ),			
			'paint' => esc_html__( 'Paint', 'better' ),			
			//'double-diagonal-left' => esc_html__( 'Double Diagonal Left', 'better' ),
			//'double-diagonal-right' => esc_html__( 'Double Diagonal Right', 'better' ),
			'round-edge' => esc_html__( 'Round Edge', 'better' ),
			'round-split-shadow' => esc_html__( 'Round Split Shadow', 'better' ),
			'round-split' => esc_html__( 'Round Split', 'better' ),			
			'triangle-big-centered' => esc_html__( 'Triangle Big - Centered ', 'better' ),
			'triangle-big-inverse' => esc_html__( 'Triangle Big - Inverse', 'better' ),
			'triangle-big-left' => esc_html__( 'Triangle Big - Left', 'better' ),
			'triangle-big-right' => esc_html__( 'Triangle Big - Right', 'better' ),
			'triangle-big-shadow-left' => esc_html__( 'Triangle Big Shadow - Left', 'better' ),
			'triangle-big-shadow-right' => esc_html__( 'Triangle Big Shadow - Right', 'better' ),
			'triangle' => esc_html__( 'Triangle', 'better' ),
			'waves' => esc_html__( 'Waves', 'better' ),
			'zigzag' => esc_html__( 'Zigzag', 'better' ),
			'zigzag-castle' => esc_html__( 'Zigzag Castle', 'better' ),
			'zigzag-incline' => esc_html__( 'Zigzag Incline', 'better' ),
			'zigzag-small' => esc_html__( 'Zigzag Small', 'better' ),
		);
	}

}
