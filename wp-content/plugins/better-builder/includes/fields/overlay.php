<?php

$this->fields = [
	'title' => [
		'title' => esc_html__( 'Title', 'better' ),
		'type' => 'text',
		'description' => esc_html__( 'This is the title for the overlay.', 'better' )
	],
	'content' => [
		'title' => esc_html__( 'Content', 'better' ),
		'type' => 'editor',
		'dynamic' => [
			'active' => true,
		],
		'default' => ''
	],
	'image' => [
		'title' => esc_html__( 'Image', 'better' ),
		'type' => 'image',
		'dynamic' => [
			'active' => true,
		],
		'description' => esc_html__( 'Select an image to be used as the background.', 'better' ),
	],
	'image_size' => [
		'title' => esc_html__( 'Image size', 'better' ),
		'type' => 'select',
		'default' => 'true',
		'options' => array_merge( array( '' => '' ), array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() ) )
	],
	'font_size' => [
		'title' => esc_html__( 'Font Size', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 12,
			'max' => 200,
			'step' => 1,
		],
		'default' => 22,
		'description' => esc_html__( 'This is the Title\'s Font Size (default size is typically styled by the theme settings)', 'better' ),
	],
	'font_color' => [
		'title' => esc_html__( 'Font Color', 'better' ),
		'type' => 'color',
		'default' => '#fff',
		'description' => esc_html__( 'This is the Title\'s Font Color (default is white)', 'better' ),
	],
	'split_title' => [
		'title' => esc_html__( 'Split Title', 'better' ),
		'type' => 'yes_no_button',
		'description' => esc_html__( 'splits the title and bolds part', 'better' )
	],
	'link' => [
		'title' => esc_html__( 'Link', 'better' ),
		'type' => 'url',
		'dynamic' => [
			'active' => true,
		],
		'placeholder' => esc_html__( 'https://your-link.com', 'better' ),
		'default' => [
			'url' => '#',
			'is_external' => false,
			'nofollow' => false,
		],
		'description' => esc_html__( 'URL, post ID, or page ID to direct the user to when clicked', 'better' )
	],
	'effect' => [
		'title' => esc_html__( 'Effect', 'better' ),
		'type' => 'select',
		'options' => array(
			'apollo' => 'Apollo',
			'bubba' => 'Bubba',
			'chico' => 'Chico',
			'dexter' => 'Dexter',
			'duke' => 'Duke',
			'goliath' => 'Goliath',
			'hera' => 'Hera',
			'honey' => 'Honey',
			'jazz' => 'Jazz',
			'julia' => 'Julia',
			'kira' => 'Kira',
			'layla' => 'Layla',
			'lily' => 'Lily',
			'marley' => 'Marley',
			'milo' => 'Milo',
			'ming' => 'Ming',
			'moses' => 'Moses',
			'oscar' => 'Oscar',
			'phoebe' => 'Phoebe',
			'romeo' => 'Romeo',
			'roxy' => 'Roxy',
			'ruby' => 'Ruby',
			'sadie' => 'Sadie',
			'sarah' => 'Sarah',
			'sarah' => 'Sarah',
			'selena' => 'Selena',
			'steve' => 'Steve',
			'terry' => 'Terry',
			'winston' => 'Winston',
			'zoe' => 'Zoe',
			'ls-accent' => 'LS Accent',
			'ls-band' => 'LS Band',
			'ls-boxed' => 'LS Boxed',
			'ls-slice' => 'LS Slice',
			'ls-x' => 'LS X',
		),
		'default' => 'apollo'
	]
];