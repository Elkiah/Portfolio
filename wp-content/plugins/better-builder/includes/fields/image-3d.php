<?php

$this->fields = array(
	'image1' => array(
		'title' => esc_html__( 'Image', 'better' ),
		'type' => 'image',
		'description' => esc_html__( 'Choose the first image in the series of images. Image names should end with _frame.extension (ex. image_1.jpg, image_2.jpg, image_3.jpg)', 'better' ),
	),
	'frames' => array(
		'title' => esc_html__( 'Frames', 'better' ),
		'type' => 'text',
		'description' => esc_html__('The total number of images to be used as frames. The higher, the smoother your interaction will be.', 'better'),
	),
	'speed' => array(
		'title' => esc_html__( 'Speed', 'better' ),
		'type' => 'text',
		'description' => esc_html__('The speed of the rotation in milliseconds delay. If you have small number of frames and the rotation seems too fast and not smooth, increase this value to 50 - 100 milliseconds delay.'),
	),
	'loading' => array(
		'title' => esc_html__( 'Loading text', 'better' ),
		'type' => 'text',
		'default' => 'Loading...',
		'description' => __('This only applies if preloadImages is true. This option let you show a loading indicator while the script is preloading the images. ', 'better'),
	),
	'preload' => array(
		'title' => esc_html__( 'Preload images', 'better' ),
		'type' => 'yes_no_button',
		'description' => esc_html__('Let the script preload all the frames on initial load.', 'better')
	)
);
