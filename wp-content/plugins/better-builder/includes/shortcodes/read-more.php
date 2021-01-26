<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Read_More extends Better_Shortcode {

    public function register_assets() {
		wp_register_style( 'better.readmore', BetterCore()->assets_url . 'shortcodes/read-more/read-more.css' );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Read More', 'better' ),
                'shortcode' => 'better_read_more',
                'content' => 'html',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
