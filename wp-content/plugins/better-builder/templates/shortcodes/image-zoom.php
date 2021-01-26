<?php
wp_enqueue_script( 'medium-zoom' );

if ( ! empty( $border_radius ) ) {
	$styles['border-radius'] = ! is_numeric( $border_radius ) ? $border_radius : $border_radius . 'px';
}

$image_id = $image;

$image = BetterCore()->get_image_src( $image_id, $image_size );
$full_image = BetterCore()->get_image_src( $image_id, null );

$attributes = array(
	'src' => $image[0],
	'data-zoom-target' => $full_image[0]
);

?>
<img <?php echo $id_attr; ?> class="better-image-zoom <?php echo $class; ?>" <?php BetterCore()->do_attributes( $attributes ); ?> <?php BetterCore()->do_style( $styles );?>>

<script>
	(function($) {
		$(document).ready(function() {
			mediumZoom('#<?php echo $id; ?>', {
				margin: <?php echo $margin ? $margin : 0; ?>,
				background: '<?php echo $overlay_color; ?>',
				scrollOffset: <?php echo $scroll_offset; ?>,
				metaClick: true
			});
		});
	})(jQuery);
</script>
