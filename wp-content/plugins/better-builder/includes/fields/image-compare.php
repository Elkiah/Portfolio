<?php

$this->fields = array(
	'image1' => array(
		'title' => esc_html__( 'Image 1', 'better' ),
		'type' => 'image',
		'description' => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'better' ),
	),
	'image2' => array(
		'title' => esc_html__( 'Image 2', 'better' ),
		'type' => 'image',
		'description' => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'better' ),
	),
	'image_size' => array(
		'title' => esc_html__( 'Image size', 'better' ),
		'type' => 'select',
		'options' => array_merge( array( '' => 'Select Image Size' ), array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() ) )
	),
	'mode' => array(
		'title' => esc_html__( 'Mode', 'better' ),
		'type' => 'select',
		'default' => 'horizontal',
		'options' => array(
			'horizontal' => __( 'Horizontal', 'better' ),
			'vertical' => __( 'Vertical', 'better' ),
			//'side_by_side' => __( 'Side-by-side', 'better' ),
			'over_and_under' => __( 'Over-and-under', 'better' ),
		)
	),
	'show_overlay' => array(
		'title' => esc_html__( 'Show overlay', 'better' ),
		'type' => 'yes_no_button',
		'default' => true,
		'group' => 'Slider'
	),
	'slider_offset' => array(
		'title' => esc_html__( 'Offset', 'better' ),
		'type' => 'range',
		'default' => 50,
		'range_settings' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1
		),
		'description' => esc_html__( '0 - 100: default is 50', 'better' ),
		'group' => 'Slider'
	),
	'show_handle' => array(
		'title' => esc_html__( 'Show handle', 'better' ),
		'type' => 'yes_no_button',
		'default' => true,
		'group' => 'Slider'
	),
	'follow_mouse' => array(
		'title' => esc_html__( 'Follow mouse', 'better' ),
		'type' => 'yes_no_button',
		'group' => 'Slider'
	),
	'before_text' => array(
		'title' => esc_html__( 'Before text', 'better' ),
		'type' => 'text',
		'group' => 'Labels'
	),
	'after_text' => array(
		'title' => esc_html__( 'After text', 'better' ),
		'type' => 'text',
		'group' => 'Labels'
	),
	'border_radius' => array(
		'title' => esc_html__( 'Border radius', 'better' ),
		'type' => 'range',
		'range_settings' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1
		),
		'description' => esc_html__( '0 - 100: default is 0', 'better' ),
		'group' => 'Design Options'
	),
);
