<style>
#<?php echo $id; ?> > .zigzag {	
	background-image: linear-gradient(45deg, <?php echo $primary_color; ?> 25%, transparent 25%), linear-gradient(-45deg, <?php echo $primary_color; ?> 25%, transparent 25%);
}
</style>

<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider zigzag <?php echo $class; ?>">	
	<div class="zigzag"></div>
</div>