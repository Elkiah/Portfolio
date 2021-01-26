<?php

$this->fields = [
	'file' => [
		'title' => esc_html__( 'File', 'better' ),
		'type' => 'file',
		'data_type' => 'json',
		'description' => esc_html__( 'Insert external url here, or upload the file on your media library and insert the url here.', 'better' )
	],
	'animation_name' => [
		'title' => esc_html__( 'Animation name', 'better' ),
		'type' => 'text'
	],
	'loop' => [
		'title' => esc_html__( 'Loop', 'better' ),
		'type' => 'yes_no_button',
	],
	'loop_count' => [
		'title' => esc_html__( 'Loop count', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 10
		],
	],
	'autoplay' => [
		'title' => esc_html__( 'Autoplay', 'better' ),
		'type' => 'yes_no_button',
	],
	'renderer' => [
		'title' => esc_html__( 'Renderer', 'better' ),
		'type' => 'select',
		'default' => 'svg',
		'options' => [
			'svg' => esc_html__( 'SVG', 'better' ),
			'canvas' => esc_html__( 'Canvas', 'better' ),
			'html' => esc_html__( 'HTML', 'better' ),
		]
	]
];