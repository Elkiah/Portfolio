<?php

$this->fields = [
	'image' => [
		'title' => esc_html__( 'Image', 'better' ),
		'type' => 'image',
		'dynamic' => [
			'active' => true,
		],
	],
	'repeat' => [
		'title' => esc_html__( 'Repeat', 'better' ),
		'type' => 'yes_no_button',
		'default' => true,
	],
	'overlay' => [
		'title' => esc_html__( 'Overlay', 'better' ),
		'type' => 'yes_no_button',
		'default' => true,
	],
	'direction' => [
		'title' => esc_html__( 'Direction', 'better' ),
		'default' => 'horizontal',
		'type' => 'select',
		'options' => [
			'horizontal' => esc_html__( 'Horizontal', 'better' ),
			'vertical' => esc_html__( 'Vertical', 'better' ),
		]
	],
	'height' => [
		'title' => esc_html__( 'Height', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 1500
		],
		'default' => 500
	]
];