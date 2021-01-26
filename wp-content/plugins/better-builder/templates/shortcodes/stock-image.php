<?php
$image = BetterCore()->get_image_src( $image, null );

$attributes = array(
	'src' => $image[0]
);

?>
<img <?php echo $id_attr; ?> class="better-stock-image <?php echo $class; ?>" <?php BetterCore()->do_attributes( $attributes ); ?>>