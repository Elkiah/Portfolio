<style>
#<?php echo $id; ?> > .boxes {
	background-image: -webkit-gradient(linear, 100% 0, 0 100%, color-stop(0.5, <?php echo $primary_color; ?>), color-stop(0.5, transparent));
	background-image: linear-gradient(to right, <?php echo $primary_color; ?> 50%, transparent 50%);
}
</style>	

<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider boxes <?php echo $class; ?>">	
	<div class="boxes"></div>
</div>