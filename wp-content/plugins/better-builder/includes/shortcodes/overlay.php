<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Overlay extends Better_Shortcode {

	public function register_assets() {
		global $pagenow, $typenow;
		
		wp_register_script( 'better.subtle', BetterCore()->assets_url . 'shortcodes/overlay/better.subtle.effect.min.js', array( 'jquery' ), '1.0.0' );
		wp_register_style( 'better.overlay', BetterCore()->assets_url . 'shortcodes/overlay/better.overlay.css' );

		// if ( current_user_can( 'edit_pages' ) && current_user_can( 'edit_posts' ) && is_admin() ) {
		// 	if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
		// 		wp_enqueue_script( 'better.subtle' );
		// 		wp_enqueue_style( 'better.overlay' );
		// 	}
		// }

		wp_enqueue_script( 'better.subtle' );
		wp_enqueue_style( 'better.overlay' );

		$effects = $this->overlay_effects();
		foreach ($effects as $key => $value) {
			$style_name = 'better.' . $key;
			wp_register_style( $style_name, BetterCore()->assets_url . 'shortcodes/overlay/subtle-' . $key . '.min.css' );
			wp_enqueue_style( $style_name );
		}
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Overlay', 'better' ),
				'shortcode' => 'better_overlay',
				'content' => 'html',
				'fields' => $this->get_fields()
			),
			$atts
		);
	}

	public static function overlay_effects() {
		return array(
				'apollo' => 'Apollo',
				'bubba' => 'Bubba',
				'chico' => 'Chico',
				'dexter' => 'Dexter',
				'duke' => 'Duke',
				'goliath' => 'Goliath',
				'hera' => 'Hera',
				'honey' => 'Honey',
				'jazz' => 'Jazz',
				'julia' => 'Julia',
				'kira' => 'Kira',
				'layla' => 'Layla',
				'lily' => 'Lily',
				'marley' => 'Marley',
				'milo' => 'Milo',
				'ming' => 'Ming',
				'moses' => 'Moses',
				'oscar' => 'Oscar',
				'phoebe' => 'Phoebe',
				'romeo' => 'Romeo',
				'roxy' => 'Roxy',
				'ruby' => 'Ruby',
				'sadie' => 'Sadie',
				'sarah' => 'Sarah',
				'sarah' => 'Sarah',
				'selena' => 'Selena',
				'steve' => 'Steve',
				'terry' => 'Terry',
				'winston' => 'Winston',
				'zoe' => 'Zoe',
				'ls-accent' => 'LS Accent',
				'ls-band' => 'LS Band',
				'ls-boxed' => 'LS Boxed',
				'ls-slice' => 'LS Slice',
				'ls-x' => 'LS X',
			);
	}

}
