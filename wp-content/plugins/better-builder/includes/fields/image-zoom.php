<?php

$this->fields = [
	'image' => [
		'title' => esc_html__( 'Image', 'better' ),
		'type' => 'image',
		'dynamic' => [
			'active' => true,
		],
	],
	'image_size' => [
		'title' => esc_html__( 'Image size', 'better' ),
		'type' => 'select',
		'options' => array_merge( array( '' => '' ), array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() ) )
	],
	'border_radius' => [
		'title' => esc_html__( 'Radius', 'better' ),
		'type' => 'range',
		'range_settings'  => [
			'min'  => 0,
			'max' => 50,
			'step' => 1
		],
	],
	'margin' => [
		'title' => esc_html__( 'Margin', 'better' ),
		'type' => 'range',
		'range_settings'  => [
			'min'  => 0,
			'max' => 100,
			'step' => 1
		],
		'default' => 0,
		'description' => esc_html__( 'Space outside the zoomed image.', 'better' ),
	],
	'overlay_color' => [
		'title' => esc_html__( 'Overlay color', 'better' ),
		'default' => '#fff',
		'type' => 'color',
	],
	'scroll_offset' => [
		'title' => esc_html__( 'Scroll offset', 'better' ),
		'type' => 'range',
		'range_settings'  => [
			'min'  => 0,
			'max' => 100,
			'step' => 1
		],
		'default' => 48,
		'description' => esc_html__( 'Number of pixels to scroll to dismiss the zoom', 'better' )
	]
];