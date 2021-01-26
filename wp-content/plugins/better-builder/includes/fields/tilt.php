<?php

$this->fields = array(
	'content' => array(
		'title' => esc_html__( 'Content', 'better' ),
		'type' => 'editor',
	),
	'content_depth' => array(
		'type' => 'range',
		'title' => esc_html__( 'Content Depth', 'better' ),
		'range_settings' => array(
			'min' => 0,
			'max' => 1000,
			'step' => 1
		),
		'description' => esc_html__( 'Set this to give the content a depth to it.', 'better' ),
		'default' => 30,
	),
	'reverse' => array(
		'type' => 'yes_no_button',
		'title' => esc_html__( 'Reverse', 'better' ),
		'description' => esc_html__( 'Reverse the tilt direction', 'better' ),
	),
	'max' => array(
		'type' => 'range',
		'title' => esc_html__( 'Max Tilt', 'better' ),
		'range_settings' => array(
			'min' => 0,
			'max' => 360,
			'step' => 1
		),
		'description' => esc_html__( 'Max tilt rotation (degrees).', 'better' ),
		'default' => '35',
	),
	'perspective' => array(
		'type' => 'range',
		'title' => esc_html__( 'Perspective', 'better' ),
		'range_settings' => array(
			'min' => 0,
			'max' => 1500,
			'step' => 1
		),
		'description' => esc_html__( 'Transform perspective, the lower the more extreme the tilt gets.', 'better' ),
		'default' => 1000,
	),
	'scale' => array(
		'type' => 'range',
		'title' => esc_html__( 'Scale', 'better' ),
		'range_settings' => array(
			'min' => 0,
			'max' => 5,
			'step' => .1
		),
		'description' => esc_html__( 'Transform scale on hover (1 = 100%, 2 = 200%, 1.5 = 150%, etc..)', 'better' ),
		'default' => '1',
	),
	'speed' => array(
		'type' => 'range',
		'title' => esc_html__( 'Speed', 'better' ),
		'default' => 500,
		'range_settings' => array(
			'min' => 0,
			'max' => 2000,
			'step' => 5
		),
		'description' => esc_html__( 'Speed of the enter/exit transition (300, 500, etc...)', 'better' ),
	),
	'transition' => array(
		'type' => 'yes_no_button',
		'title' => esc_html__( 'Transition', 'better' ),
		'default' => true,
		'description' => esc_html__( 'Set a transition on enter/exit.', 'better' ),
	),
	'axis' => array(
		'type' => 'select',
		'title' => esc_html__( 'Axis', 'better' ),
		'default' => '',
		'description' => esc_html__( 'What axis should be disabled. Can be X or Y.', 'better' ),
		'options' => array(
			'' => esc_html__('None', 'better'),
			'x' => esc_html__('X-axis', 'better'),
			'y' => esc_html__('Y-axis', 'better'),
		),
	),
	'reset' => array(
		'type' => 'yes_no_button',
		'title' => esc_html__( 'Reset', 'better' ),
		'default' => true,
		'description' => esc_html__( 'If the tilt effect has to be reset on exit.', 'better' ),
	),
	'glare' => array(
		'type' => 'yes_no_button',
		'title' => esc_html__( 'Glare', 'better' ),
		'default' => true,
		'description' => esc_html__( 'If the tilt should have a "glare" effect', 'better' ),
	),
	'max_glare' => array(
		'type' => 'range',
		'range_settings' => array(
			'min' => 0,
			'max' => 1,
			'step' => .1
		),
		'title' => esc_html__( 'Max Glare', 'better' ),
		'description' => esc_html__( 'The maximum "glare" opacity (0 - 1 ex: 1 = 100%, 0.5 = 50%)', 'better' ),
	),
	'shadow' => array(
		'type' => 'yes_no_button',
		'title' => esc_html__( 'Shadow', 'better' ),
		'description' => esc_html__( 'If the tilt should have a "shadow" effect behind it', 'better' ),
	),
	'background_type' => array(
		'type' => 'select',
		'title' => esc_html__( 'Type', 'better' ),
		'default' => 'image',
		'description' => esc_html__( 'Select the type of background you want, and then set the matching options below.', 'better' ),
		'options' => array(
			'image' => esc_html__( 'Image', 'better' ),
			'color' => esc_html__( 'Color', 'better' ),
			'linear' => esc_html__( 'Linear Gradient', 'better' ),
			'radial' => esc_html__( 'Radial Gradient', 'better' ),
		),
		'group' => esc_html__( 'Background', 'better' ),
	),
	'bg_image' => array(
		'type' => 'image',
		'title' => esc_html__( 'Image', 'better' ),
		'condition' => array(
			'background_type' => 'image'
		),
		'group' => esc_html__( 'Background', 'better' ),
	),
	'image_size' => array(
		'type' => 'select',
		'title' => esc_html__( 'Image Size', 'better' ),
		'default' => '',
		'options' => array_merge( array( '' => '' ), array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() ) ),
		'condition' => array(
			'background_type' => 'image'
		),
		'group' => esc_html__( 'Background', 'better' ),
	),
	'bg_color' => array(
		'type' => 'color',
		'title' => esc_html__( 'Background Color', 'better' ),
		'condition' => array(
			'background_type' => 'color'
		),
		'group' => esc_html__( 'Background', 'better' ),
	),
	'background_gradient_start' => array(
		'type' => 'color',
		'title' => esc_html__( 'Gradient Start Color', 'better' ),
		'condition' => array(
			'background_type' => array( 'linear', 'radial' )
		),
		'description' => esc_html__( 'Select first color for gradient.', 'better' ),
		'group' => esc_html__( 'Background', 'better' ),
	),
	'background_gradient_end' => array(
		'type' => 'color',
		'title' => esc_html__( 'Gradient End Color', 'better' ),
		'condition' => array(
			'background_type' => array( 'linear', 'radial' )
		),
		'description' => esc_html__( 'Select second color for gradient.', 'better' ),
		'group' => esc_html__( 'Background', 'better' ),
	),
	'background_gradient_angle' => array(
		'type' => 'range',
		'title' => esc_html__( 'Linear Gradient Angle', 'better' ),
		'range_settings' => array(
			'min' => 0,
			'max' => 360,
			'step' => 1
		),
		'condition' => array(
			'background_type' => ['linear', 'radial']
		),
		'description' => esc_html__( 'Enter a number between 0 and 360 (this is the angle of which the gradient will go). Only used for "Linear Gradient".', 'better' ),
		'group' => esc_html__( 'Background', 'better' ),
	),
);
