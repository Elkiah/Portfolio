<?php
if( ! empty( $height ) ) {
	$styles['height'] = is_numeric( $height ) ? $height . 'px' : $height;
}
?>

<div <?php echo $id_attr; ?> class="better-spacer <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>></div>
