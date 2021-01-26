<?php
wp_enqueue_script( 'interactive_3d' );
wp_enqueue_style( 'interactive_3d' );

$image_1 = BetterCore()->get_image_src( $image1, null );
?>

<div <?php echo $id_attr; ?>  class="better-image-3d <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>>
    <img src="<?php echo esc_attr( $image_1[0] );?>">
</div>
<script>
	(function($) {
		$(document).ready(function() {
			$('#<?php echo $id; ?>').interactive_3d({
			    frames: <?php echo empty( $frames ) ? '10' : $frames; ?>, // The total number of images to be used as frames. The higher, the smoother your interaction will be. The default value is 10 frames.
			    cursor: "move", // The CSS style to indicate what cursor will show when the user hover the object. The default value is "move"
			    speed: <?php echo empty( $speed ) ? '0' : $speed; ?>, // The speed of the rotation in milliseconds delay. If you have small number of frames and the rotation seems too fast and not smooth, increase this value to 50 - 100 milliseconds delay. The default value is 0.
			    entrance: true, // Entrance Animation. Toggle this to false to turn it off. The default value is true.
			    preloadImages: <?php echo $preload ? 'true' : 'false'; ?>, // Let the script preload all the frames on initial load. Toggle this to false to turn it off. The default value is true.
			    touchSupport: true, // The script support touch events for mobile phones. If this interferes with your website behaviour, you can toggle this to false. The default value is true.
			    loading: '<?php echo $loading; ?>', // This only applies if preloadImages is true. This option let you show a loading indicator while the script is preloading the images. The option accepts HTML. Toggle this to false to turn this off. The default value is "Loading.."
			    autoPlay: false // This option will superseded entrance option. The 3D object will start rotating automatically if autoPlay is not false. This option accepts the speed of the rotation in milliseconds delay. The default value is false.
			  });
		});
	})(jQuery);
</script>
