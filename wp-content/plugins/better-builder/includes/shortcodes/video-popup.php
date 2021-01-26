<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Video_Popup extends Better_Shortcode {
    public function register_assets() {
		wp_register_script( 'magnific-popup', BetterCore()->assets_url . 'shortcodes/video-popup/jquery.magnific-popup.min.js', array( 'jquery' ), BetterCore()->_version, true );
		wp_register_script( 'better.video-popup', BetterCore()->assets_url . 'shortcodes/video-popup/video-popup.js', array( 'jquery' ), BetterCore()->_version, true );
		wp_register_style( 'magnific-popup', BetterCore()->assets_url . 'shortcodes/video-popup/magnific-popup.css' );
		wp_register_style( 'better.video-popup', BetterCore()->assets_url . 'shortcodes/video-popup/video-popup.css' );
	}

    public function map( $builder, $function = 'map_shortcode', $atts = null ) {
        if ( ! method_exists( $builder, $function ) ) return null;

        return $builder->$function( $this,
            array(
                'name' => esc_attr__('Video Popup', 'better'),
                'shortcode' => 'better_video_popup',
                'fields' => $this->get_fields()
            ),
            $atts
        );
    }
}
