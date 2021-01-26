<?php
wp_enqueue_script( 'freezeframe' );
wp_enqueue_style( 'freezeframe' );

$image = ! empty( $image ) ? $image : $imageurl;

$image = BetterCore()->get_image_src( $image, null );

$attributes = array(
	'src' => $image[0]
);

if ( $responsive ) {
	$class .= ' freezeframe-responsive';
}

$show_overlay = !$on_hover && $show_play;

?>

<img <?php echo $id_attr; ?> class="better-gif-player <?php echo $class; ?>" <?php BetterCore()->do_attributes( $attributes ); ?>>

<script>
	(function($) {
		$(document).ready(function() {
			$('#<?php echo $id; ?>').freezeframe({
				'animation_play_duration': 'Infinity',
				'overlay': <?php echo $show_overlay  ? 'true' : 'false'; ?>,
				'non_touch_device_trigger_event': '<?php echo $on_hover ? 'hover' : 'click'; ?>',
			});
		});
	})(jQuery);
</script>
