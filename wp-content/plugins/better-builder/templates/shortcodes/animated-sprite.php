<?php

wp_enqueue_script( 'jquery.animateSprite' );

if ( !empty ( $image ) ) {

	$background_image = BetterCore()->get_image_src( $image, 'full' );
	$styles['background-image'] = "url('$background_image[0]')";
	$styles['width'] = is_numeric( $width ) ? $width . 'px' : $width;
	$styles['height'] = is_numeric( $height ) ? $height . 'px' : $height;
}

?>

<div <?php echo $id_attr; ?> class="better-animated-sprite <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>></div>

<script>
	(function($) {
		$(document).ready(function() {
			$('#<?php echo $id; ?>').animateSprite({
			    fps: <?php echo empty( $fps ) ? 12 : $fps; ?>,
			    loop: <?php echo !isset( $loop ) || !$loop ? 'false' : 'true'; ?>,
			    // animations: {
			    //     walkRight: [0, 1, 2, 3, 4, 5, 6, 7],
			    //     walkLeft: [15, 14, 13, 12, 11, 10, 9, 8]
			    // },
			});
		});
	})(jQuery);
</script>
