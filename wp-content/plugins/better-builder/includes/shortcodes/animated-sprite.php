<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Animated_Sprite extends Better_Shortcode {

	// commands used to create video sprite:
	//   ffmpeg -i "video.mp4" -f image2 -vf fps=fps=10 img%03d.jpg
	//   mogrify -resize 400 *.jpg
	//   files=$(ls img*.jpg | sort -t '-' -n -k 2 | tr '\n' ' ')
	//   convert $files -append output.jpg

	public function register_assets() {
		global $pagenow, $typenow;

		wp_register_script( 'jquery.animateSprite', BetterCore()->assets_url . 'shortcodes/animated-sprite/jquery.animateSprite.min.js', array( 'jquery' ), '1.3.5' );

		if ( current_user_can( 'edit_pages' ) && current_user_can( 'edit_posts' ) && is_admin() ) {
			if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
				// Use for Gutenberg Animated Sprite Block
				wp_enqueue_script( 'jquery.animateSprite' );
			}
		}
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Animated Sprite', 'better' ),
				'shortcode' => 'better_animated_sprite',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

}
