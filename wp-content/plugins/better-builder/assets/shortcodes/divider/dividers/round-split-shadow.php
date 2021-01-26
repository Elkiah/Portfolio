<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider round-split-shadow <?php echo $class; ?>" style="fill: <?php echo $primary_color; ?>">
	<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
		<?php if ( $location == 'top' ) : ?>
		<path d="M50 100 C49 80 47 0 40 0 L47 0 Z" fill="<?php echo $secondary_color; ?>"></path>
		<path d="M50 100 C51 80 53 0 60 0 L53 0 Z" fill="<?php echo $secondary_color; ?>"></path>
		<path d="M47 0 L50 100 L53 0 Z"></path>
		<?php else: ?>
		<path d="M50 100 C49 80 47 0 40 0 L47 0 Z" fill="<?php echo $secondary_color; ?>"></path>
		<path d="M50 100 C51 80 53 0 60 0 L53 0 Z" fill="<?php echo $secondary_color; ?>"></path>
		<path d="M47 0 L50 100 L53 0 Z"></path>
		<?php endif; ?>
	</svg>
</div>