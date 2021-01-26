<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider curve-left <?php echo $class; ?>" style="fill: <?php echo $primary_color; ?>">
	<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
		<?php if ( $location == 'top' ) : ?>
		<path d="M0 0 C 50 100 80 100 100 0 Z"/>
		<?php else: ?>
		<path d="M0 100 C 20 0 50 0 100 100 Z"></path>
		<?php endif; ?>
	</svg>
</div>