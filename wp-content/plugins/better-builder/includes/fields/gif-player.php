<?php

$this->fields = array(
	'image' => array(
		'title' => esc_html__( 'Image', 'better' ),
		'type' => 'image',
		'description' => esc_html__( 'Upload gif image.', 'better' ),
	),
	'on_hover'     => array(
		'title' => esc_html__( 'Play on hover', 'better' ),
		'type' => 'yes_no_button',
	),
	'responsive' => array(
		'title' => esc_html__( 'Responsive', 'better' ),
		'type' => 'yes_no_button',
	),
	'show_play'     => array(
		'title' => esc_html__( 'Show play button', 'better' ),
		'type' => 'yes_no_button',
	)
);
