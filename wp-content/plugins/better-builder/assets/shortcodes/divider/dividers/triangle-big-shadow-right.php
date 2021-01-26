<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider triangle-big-shadow-right <?php echo $class; ?>" style="fill: <?php echo $primary_color; ?>">
	<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
		<?php if ( $location == 'top' ) : ?>
		<path d="M0 0 L50 100 L100 0 Z"></path>
		<path d="M50 100 L100 40 L100 0 Z" style="fill: <?php echo $secondary_color; ?>"></path>
		<?php else: ?>
		<path d="M0 0 L50 100 L100 0 Z"></path>
		<path d="M50 100 L100 40 L100 0 Z" style="fill: <?php echo $secondary_color; ?>"></path>
		<?php endif; ?>
	</svg>
</div>