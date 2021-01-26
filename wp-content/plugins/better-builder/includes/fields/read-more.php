<?php

$this->fields = [
	'content' => [
		'title' => esc_html__( 'Content', 'better' ),
		'type' => 'editor',
		'dynamic' => [
			'active' => true,
		],
		'default' => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'better' ),
	],
	'height' => [
		'title' => esc_html__( 'Height', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 100,
			'max' => 1000
		],
		'default' => 120,
		'selectors' => [
			'{{WRAPPER}} .sidebar-box' => 'max-height: {{SIZE}}{{UNIT}};',
		],
	],
	'read_more_text' => [
		'title' => esc_html__( 'Read More Text', 'better' ),
		'type' => 'text',
		'default' => esc_html__( 'Read More', 'better' )
	],
	'btn_align' => [
		'title' => esc_html__( 'Button Align', 'better' ),
		'type' => 'select',
		'options' => [
			'left' => esc_html__( 'Left', 'better' ),
			'center' => esc_html__( 'Center', 'better' ),
			'right' => esc_html__( 'Right', 'better' )
		],
		'selectors' => [
			'{{WRAPPER}} .read-more' => 'text-align: {{VALUE}};',
		],
		'default' => ( function_exists( 'is_rtl' ) ? ( is_rtl() ? 'right' : 'left' ) : 'left' )
	],
	'text_color' => [
		'title' => esc_html__( 'Text Color', 'better' ),
		'type' => 'color',
		'default' => '',
		'selectors' => [
			'{{WRAPPER}} .sidebar-box' => 'color: {{VALUE}};',
		],
	],
	'btn_color' => [
		'title' => esc_html__( 'Button Color', 'better' ),
		'type' => 'color',
		'default' => '',
		'selectors' => [
			'{{WRAPPER}} .read-more > .rm-btn' => 'color: {{VALUE}};',
		],
	],
	'fading_color' => [
		'title' => esc_html__( 'Fading Color', 'better' ),
		'type' => 'color',
		'selectors' => [
			'{{WRAPPER}} .read-more' => 'linear-gradient(to bottom, transparent 0%, {{VALUE}} 45%, {{VALUE}} 100%)',
		],
	]
];
