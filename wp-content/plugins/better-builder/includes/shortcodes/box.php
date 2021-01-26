<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Box extends Better_Shortcode {

	public function register_assets() {
		global $pagenow, $typenow;

		wp_register_script( 'backgroundvideo', BetterCore()->assets_url . 'shortcodes/box/backgroundVideo/backgroundVideo.min.js', array( 'jquery' ), '0.2.5', true );
		wp_register_script( 'better.contentsection', BetterCore()->assets_url . 'shortcodes/box/better.contentsection.min.js', array( 'jquery' ), '1.0.0', true );
		wp_register_script( 'better.lazyload', BetterCore()->assets_url . 'shortcodes/box/better.lazyload.min.js', array( 'jquery', 'lazyloadxt' ), '1.0.0', true );
		wp_register_script( 'jquery.appear', BetterCore()->assets_url . 'shortcodes/box/jquery-appear/jquery.appear.min.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'lazyloadxt', BetterCore()->assets_url . 'shortcodes/box/lazyloadxt/dist/jquery.lazyloadxt.min.js', array( 'jquery' ), '1.0.5', true );
		wp_register_script( 'okvideo', BetterCore()->assets_url . 'shortcodes/box/okvideo/src/okvideo.min.js', array( 'jquery' ), '2.3.2', true );
		wp_register_script( 'skrollr', BetterCore()->assets_url . 'shortcodes/box/skrollr/src/skrollr.min.js', null, '0.6.30', true );
		wp_register_script( 'videoBG', BetterCore()->assets_url . 'shortcodes/box/jquery.videoBG/jquery.videoBG.min.js', array( 'jquery' ), '0.2.1', true );

		if ( current_user_can( 'edit_pages' ) && current_user_can( 'edit_posts' ) && is_admin() ) {
			if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
				// Use for Gutenberg Box Block
				wp_enqueue_script( 'videoBG' );
				wp_enqueue_script( "okvideo" );
			}
		}
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Box', 'better' ),
				'shortcode' => 'better_box',
				'container' => true,
				//'content' => 'html',
				'fields' => array(
					'size' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Size', 'better' ),
						'default' => 'fullboxed', //full, partial, fullboxed (this will be used when parallax is called from image shortcode)
						'options' => array(
							'fullboxed' => esc_html__( '100% Full Width with Boxed Content', 'better' ),
							'full' => esc_html__( '100% Full Width', 'better' ),
							'partial' => esc_html__( 'Partial Width with Boxed Content', 'better' ),
						)
					),
					'background_type' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Background Type', 'better' ),
						'options' => array(
							'' => esc_html__( 'None', 'better' ),
							'color' => esc_html__( 'Color', 'better' ),
							'image' => esc_html__( 'Image', 'better' ),
							'video' => esc_html__( 'WordPress Video', 'better' ),
							'webvideo' => esc_html__( 'Youtube/Vimeo Video', 'better' )
						)
					),
					'image' => array(
						'type' => 'image',
						'title' => esc_html__( 'Image', 'better' ),
						'class' => 'imagesettings',
						'skinnable' => false,
						'toolset' => true,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'image'
						),
					),
					'imagesize' => array(
						'type' => 'image_size',
						'title' => esc_html__( 'Image Size', 'better' ),
						'default' => 'large1600',
						'class' => 'imagesettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'image'
						),
					),
					'imagemode' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Background Mode', 'better' ),
						'default' => 'full',
						'options' => array(
							'full' => esc_html__( 'Full', 'better' ),
							'parallax' => esc_html__( 'Parallax', 'better' ),
							'fixed' => esc_html__( 'Parallax - Fixed', 'better' ),
							'repeat' => esc_html__( 'Repeat', 'better' ),
							'repeat-x' => esc_html__( 'Repeat-X', 'better' ),
							'repeat-y' => esc_html__( 'Repeat-Y', 'better' ),
							'zoom-in' => esc_html__( 'Zoom In', 'better' ),
						),
						'class' => 'imagesettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'image'
						),
					),
					'background_image_position' => array(
						'type' => 'text',
						'title' => esc_html__( 'Background Image Position', 'better' ),
						'description' => esc_html__( 'sets the background image position according to the background-image CSS property rules', 'better' ),
						'default' => '50% 50%',
						'class' => 'fullimagesettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'image'
						),
					),
					'image_horizontal_position' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Horizontal Position', 'better' ),
						'default' => '',
						'options' => array(
							'' => esc_html__( 'Left', 'better' ),
							'right' => esc_html__( 'Right', 'better' ),
							'center' => esc_html__( 'Center', 'better' ),
						),
						'class' => 'parallaxsettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'image'
						),
					),
					'speed' => array(
						'type' => 'text',
						'title' => esc_html__( 'Parallax Speed', 'better' ),
						'description' => esc_html__( 'Parallax mode only - default 2', 'better' ),
						'default' => 2,
						'class' => 'parallaxsettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'image'
						),
					),
					'poster' => array(
						'type' => 'image',
						'title' => esc_html__( 'Poster Image', 'better' ),
						'description' => esc_html__( 'required for WordPress video', 'better' ),
						'class' => 'videosettings',
						'skinnable' => false,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'video'
						),
					),
					'mp4' => array(
						'type' => 'text',
						'title' => esc_html__( 'MP4 Video', 'better' ),
						'class' => 'videosettings',
						'skinnable' => false,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'video'
						),
					),
					//'mp4_id' => array( 'type' => 'deprecated', 'description' => 'use mp4 instead' ),
					//'mp4_url' => array( 'type' => 'deprecated', 'description' => 'use mp4 instead' ),
					'ogv' => array(
						'type' => 'text',
						'title' => esc_html__( 'OGV Video', 'better' ),
						'class' => 'videosettings',
						'skinnable' => false,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'video'
						),
					),
					//'ogv_id' => array( 'type' => 'deprecated', 'description' => 'use ogv instead' ),
					//'ogv_url' => array( 'type' => 'deprecated', 'description' => 'use ogv instead' ),
					'webm' => array(
						'type' => 'text',
						'title' => esc_html__( 'WebM Video', 'better' ),
						'class' => 'videosettings',
						'skinnable' => false,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'video'
						),
					),
					//'webm_id' => array( 'type' => 'deprecated', 'description' => 'use webm instead' ),
					//'webm_url' => array( 'type' => 'deprecated', 'description' => 'use webm instead' ),
					'video_speed' => array(
						'type' => 'text',
						'title' => esc_html__( 'Parallax Speed', 'better' ),
						'description' => esc_html__( 'Parallax mode only - default 0 (0 is normal video background, 1 is fixed video background, or greater than 1 is parallax video background)', 'better' ),
						'default' => '0',
						'class' => 'videosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'video'
						),
					),
					'mute_volume' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Mute Volume', 'better' ),
						'description' => esc_html__( 'When checked, the volume will be muted. Default is Off. Only applies to videos with a Parallax Speed of 1 or greater.', 'better' ),
						'default' => false,
						'class' => 'videosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'video'
						),
					),
					'video' => array(
						'type' => 'text',
						'title' => esc_html__( 'Video', 'better' ),
						'description' => esc_html__( 'Youtube or Vimeo video ID or URL', 'better' ),
						'class' => 'okvideosettings',
						'skinnable' => false,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'autoplay' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Autoplay', 'better' ),
						'description' => esc_html__( 'When enabled, the video will play automatically.', 'better' ),
						'default' => true,
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'volume' => array(
						'type' => 'text',
						'title' => esc_html__( 'Volume', 'better' ),
						'description' => esc_html__( 'Video volume level (0 - 100)', 'better' ),
						'default' => '50',
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'volumebutton' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Show Volume On/Off Button', 'better' ),
						'description' => esc_html__( 'When enabled, the volume on/off button will be visible.', 'better' ),
						'default' => false,
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'playbutton' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Show Play/Pause Button', 'better' ),
						'description' => esc_html__( 'When enabled, the play/pause button will be visible.', 'better' ),
						'default' => false,
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'restartbutton' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Show Restart Button', 'better' ),
						'description' => esc_html__( 'When enabled, the restart button will be visible.', 'better' ),
						'default' => false,
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'button_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Button Color', 'better' ),
						'description' => esc_html__( 'Color to be used for volume, play, and restart buttons.', 'better' ),
						'default' => '#ffffff',
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'button_font_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Button Font Color', 'better' ),
						'description' => esc_html__( 'Color to be used for the font on the volume, play, and restart buttons.', 'better' ),
						'default' => '#999999',
						'class' => 'okvideosettings',
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'button_position' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Button Position', 'better' ),
						'options' => array(
							'bottomleft' => esc_html__( 'Bottom Left', 'better' ),
							'bottomright' => esc_html__( 'Bottom Right', 'better' ),
							'bottomcenter' => esc_html__( 'Bottom Center', 'better' ),
							'topleft' => esc_html__( 'Top Left', 'better' ),
							'topright' => esc_html__( 'Top Right', 'better' ),
							'topcenter' => esc_html__( 'Top Center', 'better' )
						),
						'default' => 'bottomleft',
						'class' => 'okvideosettings',
						'description' => esc_html__( 'This is where the buttons that are enabled above will display over the content section.', 'better' ),
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'webvideo'
						),
					),
					'background_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Background Color', 'better' ),
						'class' => 'colorsettings',
						'toolset' => true,
						'dependency' => array(
							'element' => 'background_type',
							'value' => 'color'
						),
					),
					'box_class' => array(
						'type' => 'text',
						'title' => esc_html__( 'Boxed Content Class', 'better' ),
						'description' => esc_html__( 'optional css class name to apply to the boxed content within the content section', 'better' ),
						'skinnable' => false
					),
					'height' => array(
						'type' => 'range',
						'title' => esc_html__( 'Height', 'better' ),
						'description' => esc_html__( 'optional - manually sets the height of the content section', 'better' ),
					),
					'full_height' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Full Height', 'better' ),
						'description' => esc_html__( 'automatically sized to the window height', 'better' ),
					),
					'vertical_align' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Vertical Align', 'better' ),
						'description' => esc_html__( 'vertically aligns the content in the center (applies to full height or manual height content sections)', 'better' ),
					),
					'height_adjustment' => array(
						'type' => 'text',
						'title' => esc_html__( 'Height Adjustment', 'better' ),
						'description' => esc_html__( 'subtracted from computed full height', 'better' ),
					),
					'breakout' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Break Out', 'better' ),
						'description' => esc_html__( 'tries to break out of its parent container to force full width. May be needed if theme doesn\'t support full width pages/posts', 'better' ),
						'skinnable' => false
					),
					'class' => array(
						'type' => 'text',
						'title' => esc_html__( 'CSS Class', 'better' ),
					),
					'margin_top' => array(
						'type' => 'range',
						'title' => esc_html__( 'Margin Top', 'better' ),
						'group' => esc_html__( 'Layout', 'better' ),
					),
					'margin_bottom' => array(
						'type' => 'range',
						'title' => esc_html__( 'Margin Bottom', 'better' ),
						'group' => esc_html__( 'Layout', 'better' ),
					),
					'padding_top' => array(
						'type' => 'text',
						'title' => esc_html__( 'Padding Top', 'better' ),
						'group' => esc_html__( 'Layout', 'better' ),
					),
					'padding_bottom' => array(
						'type' => 'text',
						'title' => esc_html__( 'Padding Bottom', 'better' ),
						'group' => esc_html__( 'Layout', 'better' ),
					),

					// Overlay Tab
					'overlay_image' => array(
						'type' => 'image',
						'title' => esc_html__( 'Image', 'better' ),
						'description' => esc_html__( 'Use a semi-transparent image to overlay the background image.', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Color', 'better' ),
						'description' => esc_html__( 'This color will overlay the background image. Make sure to set an opacity.', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_opacity' => array(
						'type' => 'text',
						'title' => esc_html__( 'Opacity', 'better' ),
						'description' => esc_html__( '0 - 100: leave blank for solid color (used with Color).', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Gradient Type', 'better' ),
						'options' => array(
							'' => esc_html__( 'None', 'better' ),
							'linear' => esc_html__( 'Linear', 'better' ),
							'radial' => esc_html__( 'Radial', 'better' )
						),
						'description' => esc_html__( 'This option will set an overlay that has a gradient effect. For best results, set all gradient options below.', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_direction' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Overlay Gradient Direction', 'better' ),
						'options' => array(
							'top' => esc_html__( 'Top to Bottom', 'better' ),
							'bottom' => esc_html__( 'Bottom to Top', 'better' ),
							'right' => esc_html__( 'Left to Right', 'better' ),
							'left' => esc_html__( 'Right to Left', 'better' ),
							'bottom_right' => esc_html__( 'Diagonal Left Top to Right Bottom', 'better' ),
							'bottom_left' => esc_html__( 'Diagonal Left Bottom to Right Top', 'better' ),
							'top_right' => esc_html__( 'Diagonal Right Top to Left Bottom', 'better' ),
							'top_left' => esc_html__( 'Diagonal Right Bottom to Left Top', 'better' ),
						),
						'default' => 'top',
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_start_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Gradient Start Color', 'better' ),
						'description' => esc_html__( 'This color will overlay the background image. Make sure to set the gradient start opacity.', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_start_opacity' => array(
						'type' => 'text',
						'title' => esc_html__( 'Gradient Start Opacity', 'better' ),
						'description' => esc_html__( '0 - 100: leave blank for solid color (used with Gradient Start Color).', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_start_percent' => array(
						'type' => 'range',
						'title' => esc_html__( 'Gradient Start Percent', 'better' ),
						'description' => esc_html__( '0 - 100: this will be the percent where you want the gradient color to start (example 50).', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_end_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Gradient End Color', 'better' ),
						'description' => esc_html__( 'This color will overlay the background image. Make sure to set the gradient end opacity.', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_end_opacity' => array(
						'type' => 'range',
						'title' => esc_html__( 'Gradient End Opacity', 'better' ),
						'description' => esc_html__( '0 - 100: leave blank for solid color (used with Gradient End Color).', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					'overlay_gradient_end_percent' => array(
						'type' => 'range',
						'title' => esc_html__( 'Gradient End Percent', 'better' ),
						'description' => esc_html__( '0 - 100: this will be the percent where you want the gradient color to end (example 50).', 'better' ),
						'group' => esc_html__( 'Overlay', 'better' ),
					),
					// Top Separator
					'top_divider_type' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Type', 'better' ),
						'options' => BetterSC_Divider::separator_types(),
						'group' => esc_html__( 'Top Spearator', 'better' ),
					),
					'top_divider_primary_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Primary Color', 'better' ),
						'group' => esc_html__( 'Top Spearator', 'better' ),
					),
					'top_divider_secondary_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Secondary Color', 'better' ),
						'description' => esc_html__( 'may not apply to most divider types', 'better' ),
						'group' => esc_html__( 'Top Spearator', 'better' ),
					),
					'top_divider_tertiary_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Tertiary Color', 'better' ),
						'description' => esc_html__( 'may not apply to most divider types', 'better' ),
						'group' => esc_html__( 'Top Spearator', 'better' ),
					),
					// Bottom Separator
					'bottom_divider_type' => array(
						'type' => 'dropdown',
						'title' => esc_html__( 'Type', 'better' ),
						'options' => BetterSC_Divider::separator_types(),
						'group' => esc_html__( 'Bottom Spearator', 'better' ),
					),
					'bottom_divider_primary_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Primary Color', 'better' ),
						'group' => esc_html__( 'Bottom Spearator', 'better' ),
					),
					'bottom_divider_secondary_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Secondary Color', 'better' ),
						'description' => esc_html__( 'may not apply to most divider types', 'better' ),
						'group' => esc_html__( 'Bottom Spearator', 'better' ),
					),
					'bottom_divider_tertiary_color' => array(
						'type' => 'color',
						'title' => esc_html__( 'Tertiary Color', 'better' ),
						'description' => esc_html__( 'may not apply to most divider types', 'better' ),
						'group' => esc_html__( 'Bottom Spearator', 'better' ),
					),
				)
			),
			$atts
		);
	}

}
