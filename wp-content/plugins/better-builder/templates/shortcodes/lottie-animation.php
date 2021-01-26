<?php

wp_enqueue_script( 'bodymovin' );

$path = $file;
$loop = !isset( $loop ) || !$loop ? 'false' : 'true';
$loop = !empty( $loop_count ) ? $loop_count : $loop;

$animation_name = !empty( $animation_name ) ? $animation_name : $id;

?>
<div <?php echo $id_attr; ?>  class="better-lottie <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>></div>

<script>
	(function($) {
		$(document).ready(function() {
			bodymovin.loadAnimation({
			  container: document.getElementById('<?php echo $id; ?>'), // the dom element that will contain the animation
			  renderer: '<?php echo $renderer; ?>',
			  name: '<?php echo $animation_name; ?>',
		  	  loop: <?php echo $loop; ?>,
			  autoplay: <?php echo !isset( $autoplay ) || !$autoplay ? 'false' : 'true'; ?>,
			  path: '<?php echo $path; ?>' // the path to the animation json
			});
		});
	})(jQuery);
</script>
