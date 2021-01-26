<?php

wp_enqueue_script( 'vanilla.tilt' );
wp_enqueue_style( 'better.tilt' );

$styles['position'] = "relative";
$inner = 'better_tilt_inner';
$outer = 'better_tilt_outer';
$content_styles['position'] = 'absolute';
$content_styles['width'] = '100%';

if ( $background_type == 'image' && !empty( $bg_image ) ) {
	$bg_image = BetterCore()->get_image_src( $bg_image, $image_size );
}

if ( $background_type == 'color' && !empty( $bg_color ) ) {
	$styles['background-color'] = $bg_color;
}

if ( $background_type == 'linear' && ( !empty( $background_gradient_start ) && !empty( $background_gradient_end ) ) ) {
	$styles['background-color'] = $background_gradient_start;
	$styles['background-image'] = 'linear-gradient(' . $background_gradient_angle . 'deg, ' . $background_gradient_start . ' 0%, ' . $background_gradient_end . ' 100%)';
}

if ( $background_type == 'radial' && ( !empty( $background_gradient_start ) && !empty( $background_gradient_end ) ) ) {
	$styles['background-color'] = $background_gradient_start;
	$styles['background-image'] = 'radial-gradient(circle at center, ' . $background_gradient_start . ' 0%, ' . $background_gradient_end . ' 100%)';
}

if ( !empty( $background_type ) && $background_type != 'image' ) {
	wp_enqueue_script( 'better.tilt' );
	$inner = 'better_tilt_inner_resize';
	$outer = 'better_tilt_outer_resize';
}

$content_depth = is_numeric( $content_depth ) ? $content_depth . 'px' : $content_depth;

if ( !empty( $content_depth ) ) {
	$content_styles['-webkit-transform'] = 'translateZ(' . $content_depth . ')';
	$content_styles['transform'] = 'translateZ(' . $content_depth . ')';
}

$attributes = array(
	'data-tilt-reverse' => !$reverse ? 'false' : $reverse,
	'data-tilt-max' => $max,
	'data-tilt-perspective' => $perspective,
	'data-tilt-scale' => $scale,
	'data-tilt-speed' => $speed,
	'data-tilt-transition' => !$transition ? 'false' : $transition,
	'data-tilt-axis' => $axis,
	'data-tilt-reset' => !$reset ? 'false' : $reset,
	'data-tilt-glare' => $glare,
	'data-tilt-max-glare' => $max_glare,
);

?>

<div <?php echo $id_attr; ?> class="better <?php echo $class; ?>" data-tilt="true" <?php BetterCore()->do_attributes( $attributes ); ?> style="transform-style: preserve-3d;">

	<div <?php BetterCore()->do_style( $styles );?> class="<?php echo $outer; ?>">

		<?php if ( !empty( $content ) ) { ?>

		<div class="<?php echo $inner; ?>" <?php BetterCore()->do_style( $content_styles );?>><?php echo do_shortcode( $content ); ?></div>

		<?php } ?>

		<?php if ( $background_type == 'image' && !empty( $bg_image ) ) { ?>

		<img src="<?php echo $bg_image[0]; ?>" alt="" />

		<?php } ?>

	</div>
</div>


<?php if ( !empty( $shadow ) && $shadow == 'yes' ) { ?>
<style>
	<?php echo '#' . $id; ?>:after {
	    content: '';
	    position: absolute;
	    top: 0;
	    left: 0;
	    height: 100%;
	    width: 100%;
	    background-color: #333;
	    box-shadow: 0 20px 70px -10px rgba(51, 51, 51, 0.7), 0 50px 100px 0 rgba(51, 51, 51, 0.2);
	    z-index: -1;
	    -webkit-transform: translateZ(-50px);
	    transform: translateZ(-50px);
	    -webkit-transition: .3s;
	    transition: .3s;
	}
</style>
<?php } ?>

<!-- <script type="text/javascript">
	var resizeRoutinesCounter = 10;

	jQuery(document).ready(function ($) {

		var resizeRoutines = function() {
			$('.better_tilt_outer_resize').each(function() {
				var self = $(this);
				var inner = self.find('.better_tilt_inner_resize');
				// fallback for resize
				//self.removeAttr('style');
				var outerWidth = self.outerHeight();
				var innerWidth = inner.outerHeight();
				if(innerWidth > outerWidth){
					self.height(innerWidth);
				}
			});
		}

		setInterval(function() {
			if(resizeRoutinesCounter) {
				resizeRoutinesCounter--;
				resizeRoutines();
			}
		}, 500);

		$(window).resize(function(){
			resizeRoutinesCounter = 10;
		});
	});
</script> -->
