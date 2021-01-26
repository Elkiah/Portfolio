<style>
#<?php echo $id; ?> > .diamond-tripple {
	box-shadow: -50px 50px 0 <?php echo $secondary_color; ?>, 50px -50px 0 <?php echo $tertiary_color; ?>;	
}
</style>	

<div id="<?php echo $id; ?>" class="intense <?php echo $location; ?>-divider diamond-tripple <?php echo $class; ?>">	
	<div class="diamond-tripple" style="background: <?php echo $primary_color; ?>"></div>
</div>