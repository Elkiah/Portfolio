<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Posts extends Better_Shortcode {

	public function register_assets() {
		wp_register_script( 'waypoints', BetterCore()->assets_url . 'shortcodes/posts/jquery.waypoints.min.js', array( 'jquery' ), '4.0.1', true );
		wp_register_script( 'isotope-masonry', BetterCore()->assets_url . 'shortcodes/posts/isotope.pkgd.min.js', array( 'jquery' ), '3.0.1', true );
		wp_register_script( 'isotope-packery', BetterCore()->assets_url . 'shortcodes/posts/packery-mode.pkgd.min.js', array( 'jquery', 'imagesloaded', 'isotope-masonry' ), '4.2.2', true );

		wp_register_script( 'swiper', BetterCore()->assets_url . 'shortcodes/posts/swiper/js/swiper.min.js', array( 'jquery' ), '3.4.2', true );
		wp_register_style( 'swiper', BetterCore()->assets_url . 'shortcodes/posts/swiper/css/swiper.min.css' );
		
		wp_register_script( 'better.posts', BetterCore()->assets_url . 'shortcodes/posts/better-posts.js', array( 'jquery', 'imagesloaded' ), '1.0.0', true );

		if ( is_admin() && class_exists( 'ET_Builder_Module' ) ) {
			wp_enqueue_script( 'better.divi-posts', BetterCore()->assets_url . 'js/divi/posts.js', array( 'jquery', 'jquery-ui-autocomplete' ), '1.0.0', true );
		}

		wp_localize_script( 'better.posts', '$better_posts', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		) );
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;
		
		return $builder->$function( $this, 
			array (
				'name' => esc_attr__( 'Posts', 'better' ),
				'shortcode' => 'better_posts',
				'fs_admin_enqueue_js' => BetterCore()->assets_url . 'shortcodes/posts/better-post-type.js',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
