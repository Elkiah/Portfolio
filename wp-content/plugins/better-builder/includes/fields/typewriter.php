<?php

$this->fields = [
	'content' => [
		'title' => esc_html__( 'Text', 'better' ),
		'type' => 'textarea',
		'js_composer' => false,
		'default' => esc_html__( 'Add Your Heading Text Here', 'better' ),
	],
	'type_speed' => [
		'title' => esc_html__( 'Type speed', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 1000,
			'step' => 5,
		],
		'default' => 100,
	],
	'back_speed' => [
		'title' => esc_html__( 'Back speed', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 1000,
			'step' => 5,
		],
		'default' => 50,
	],
	'start_deley' => [
		'title' => esc_html__( 'Start delay', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 1000,
			'step' => 5,
		],
		'default' => 500,
	],
	'back_deley' => [
		'title' => esc_html__( 'Back delay', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 1000,
			'step' => 5,
		],
		'default' => 500,
	],
	'loop' => [
		'title' => esc_html__( 'Loop', 'better' ),
		'type' => 'yes_no_button',
	],
	'show_cursor' => [
		'title' => esc_html__( 'Show cursor', 'better' ),
		'type' => 'yes_no_button',
		'default' => true
	],
	'blinking_cursor' => [
		'title' => esc_html__( 'Blinking cursor', 'better' ),
		'type' => 'yes_no_button',
		'default' => true,
	],
	'cursor' => [
		'title' => esc_html__( 'Cursor', 'better' ),
		'type' => 'text',
		'default' => '|'
	]
];