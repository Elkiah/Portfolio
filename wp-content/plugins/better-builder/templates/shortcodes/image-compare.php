<?php

// if ( !empty ( $background_image ) ) {

// 	$image_size = empty( $background_image_size ) ? 'full' : $background_image_size;
// 	$background_image_src = is_numeric( $background_image ) ? wp_get_attachment_image_src( $background_image, $image_size ) : null;
// 	$background_image = !empty( $background_image_src ) ? $background_image_src[0] : $background_image;

// 	$styles['background-image'] = "url('$background_image')";

// }

//[better_image_compare_x image1="73" image2="72" image_size="large" slider_offset="50" show_overlay="true" show_handle="true" before_text="Before" after_text="After" border_radius="3px"]

$slider_offset *= 0.01;

wp_enqueue_script( 'twentytwenty' );
wp_enqueue_script( 'jquery.event.move' );
wp_enqueue_style( 'twentytwenty' );

if ( empty( $follow_mouse ) ) $follow_mouse = '0';

$style = '';

$image1 = BetterCore()->get_image_src( $image1, $image_size );
$image2 = BetterCore()->get_image_src( $image2, $image_size );

if ( ! empty( $border_radius ) ) {
	$styles['border-radius'] = is_numeric( $border_radius ) ? $border_radius . 'px' : $border_radius;
}

/*
		 if ( $mode == 'side_by_side' ) {
			$output .= do_shortcode('
			[intense_row margin_top="0" margin_bottom="0" padding_top="0" padding_bottom="0" nogutter="1"]
				[intense_column size="6" medium_size="6" small_size="6" extra_small_size="6" nogutter="1"]' . intense_run_shortcode( 'intense_image', array('image' => $image1, 'size' => $size ) ) . '[/intense_column]
				[intense_column size="6" medium_size="6" small_size="6" extra_small_size="6" nogutter="1"]' . intense_run_shortcode( 'intense_image', array('image' => $image2, 'size' => $size ) ) . '[/intense_column]
			[/intense_row]
			');

			$style .= '
				#' . $id . ' img {
					position: relative !important;
				}
				#' . $id . ' img:last-of-type{
					border-left: 3px solid #FFF;
					margin-left: -3px;
				}
			';
		}

		$output .= '</div>';



		*/
?>
<?php if ( $mode == 'horizontal' || $mode == 'vertical' ) { ?>
<script>
	jQuery(document).ready(function($) {
		var $el = $('#<?php echo $id; ?>');
		var $image1 = $el.children().first();

	  	$el.twentytwenty({
	  		default_offset_pct: <?php echo $slider_offset; ?>,
	  		orientation: '<?php echo $mode; ?>',
	  		follow_mouse: <?php echo $follow_mouse; ?>
  		});

	  	$el.width($image1.width());

	  	<?php if ( !$show_overlay ) { ?>
	  		$el.children('.twentytwenty-overlay').remove();
  		<?php } ?>

  		<?php if ( !$show_handle ) { ?>
  			$el.find(".twentytwenty-handle").css({ "border": "none", "box-shadow": "none" }).children().remove();
  		<?php } ?>
	});
</script>
<?php } ?>

<style>
<?php if ( $mode == 'over_and_under' ) { ?>

	#<?php echo $id; ?> img {
		position: relative !important;
	}
	#<?php echo $id; ?> img:last-of-type {
		border-top: 3px solid #FFF;
		margin-top: -3px;
	}

<?php } ?>
<?php if ( $show_overlay ) { ?>

	#<?php echo $id; ?> .twentytwenty-before-label:before {
		content: "<?php echo $before_text; ?>";
	}
	#<?php echo $id; ?> .twentytwenty-after-label:before {
		content: "<?php echo $after_text; ?>";
	}

<?php } ?>
<?php if ( !$show_handle && $mode == 'horizontal' ) { ?>

	#<?php echo $id; ?> .twentytwenty-handle:before {
		bottom: 0;
	}
	#<?php echo $id; ?> .twentytwenty-handle:after {
		top: 0;
	}

<?php } ?>
<?php if ( !$show_handle && $mode == 'vertical' ) { ?>

	#<?php echo $id; ?> .twentytwenty-handle:before {
		left: 0;
		margin-left: 0;
	}
	#<?php echo $id; ?> .twentytwenty-handle:after {
		right: 0;
	}

<?php } ?>
</style>

<div <?php echo $id_attr; ?>  class="better-image-compare twentytwenty-container <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>>
	<img width="<?php echo $image1[1]; ?>" height="<?php echo $image1[2]; ?>" src="<?php echo $image1[0]; ?>" />
	<img width="<?php echo $image2[1]; ?>" height="<?php echo $image2[2]; ?>"  src="<?php echo $image2[0]; ?>" />
</div>
