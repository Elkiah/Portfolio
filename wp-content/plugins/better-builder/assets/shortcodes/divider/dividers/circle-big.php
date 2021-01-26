<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider circle-big <?php echo $class; ?>" style="fill: <?php echo $primary_color; ?>">
	<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
		<?php if ( $location == 'top' ) : ?>
		<path d="M0 100 C40 0 60 0 100 100 Z"></path>
		<?php else: ?>
		<path d="M0 100 C40 0 60 0 100 100 Z"></path>
		<?php endif; ?>
	</svg>
</div>