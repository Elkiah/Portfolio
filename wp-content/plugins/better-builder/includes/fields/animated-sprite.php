<?php

$this->fields = array(
	'image' => array(
		'title' => esc_html__( 'Image', 'better' ),
		'type' => 'image',
		'description' => esc_html__( 'Upload your desired image.', 'better' ),
	),
	'width' => array(
		'title' => esc_html__( 'Width', 'better' ),
		'type' => 'range',
		'range_settings' => array(
			'min'  => 1,
			'max'  => 1000,
			'step' => 1,
		),
		'default' => 100,
	),
	'height' => array(
		'title' => esc_html__( 'Height', 'better' ),
		'type' => 'range',
		'range_settings' => array(
			'min'  => 1,
			'max'  => 1000,
			'step' => 1,
		),
		'default' => 100,
	),
	'fps' => array(
		'title' => esc_html__( 'Frames per second', 'better' ),
		'type' => 'range',
		'range_settings' => array(
			'min'  => 1,
			'max'  => 20,
			'step' => 1,
		),
		'default' => 12,
	),
	'loop' => array(
		'title' => esc_html__( 'Loop', 'better' ),
		'type' => 'yes_no_button',
	),
);
