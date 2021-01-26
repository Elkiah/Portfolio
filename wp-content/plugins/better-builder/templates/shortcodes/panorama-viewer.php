<?php
wp_enqueue_script( 'panorama-viewer' );
wp_enqueue_style( 'panorama-viewer' );

if ( ! empty( $height ) ) {
	$styles['height'] = ! is_numeric( $height ) ? $height : $height . 'px';
}

$image = BetterCore()->get_image_src( $image, null );

$attributes = array(
	'src' => $image[0]
);

?>
<div <?php echo $id_attr; ?> class="better-panorama-viewer panorama <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>>
	<img <?php BetterCore()->do_attributes( $attributes ); ?>>
</div>
<script>
	(function($) {
		$(document).ready(function() {
			$('#<?php echo $id; ?>').panorama_viewer({
				// The image will repeat when the user scroll reach the bounding box. The default value is false.
				repeat: <?php echo $repeat ? 'true' : 'false'; ?>,
				// Let you define the direction of the scroll. Acceptable values are "horizontal" and "vertical". The default value is horizontal
				direction: "<?php echo $direction; ?>",
				// This allows you to set the easing time when the image is being dragged. Set this to 0 to make it instant. The default value is 700.
				animationTime: 700,
				// You can define the easing options here. This option accepts CSS easing options. Available options are "ease", "linear", "ease-in", "ease-out", "ease-in-out", and "cubic-bezier(...))". The default value is "ease-out".
				easing: "ease-out",
				// Toggle this to false to hide the initial instruction overlay
				overlay: <?php echo $overlay ? 'true' : 'false'; ?>
			});

		});
	})(jQuery);
</script>
