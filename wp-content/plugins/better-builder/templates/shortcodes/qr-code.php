<?php

$attributes = array(
	'src' => 'https://chart.googleapis.com/chart?chs=' . $width . 'x' . $width . '&cht=qr&chld=L|0&chl=' . urlencode( $data ),
	'width' => $width,
	'height' => $width
);
?>

<img <?php echo $id_attr; ?> class="better-qr-code <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?> <?php BetterCore()->do_attributes( $attributes ); ?>>
