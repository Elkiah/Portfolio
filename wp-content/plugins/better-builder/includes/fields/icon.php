<?php

$sizes = [];
for ($i=1; $i <= 5 ; $i++) {
	$sizes[ $i ] = $i;
}

$this->fields = array(
	'source' => array(
		'title' => esc_html__( 'Icons Source', 'better' ),
		'type' => 'icon_source',
		'default' => 'font-awesome'
	),
	'type' => array(
		'title' => esc_html__( 'Icons', 'better' ),
		'type' => 'icon'
	),
	'align' => array(
		'type' => 'select',
		'title' =>  esc_html__( 'Button Align', 'better' ),
		'options' => array(
			'none' => esc_html__( 'None', 'better' ),
			'left' => esc_html__( 'Left', 'better' ),
			'right' => esc_html__( 'Right', 'better' ),
			'center' => esc_html__( 'Center', 'better' ),
		)
	),
	'title' => array(
		'title' => esc_html__( 'Title', 'better' ),
		'type' => 'text',
	),
	'size' => array(
		'title' => esc_html__( 'Size', 'better' ),
		'type' => 'select',
		'options' => $sizes
	),
	'width' => array(
		'title' => esc_html__( 'Width', 'better' ),
		'type' => 'range',
		'range_settings'  => array(
			'min'  => 1,
			'max' => 100,
			'step' => 1
		),
	),
	'height' => array(
		'title' => esc_html__( 'Height', 'better' ),
		'type' => 'range',
		'range_settings'  => array(
			'min'  => 1,
			'max' => 100,
			'step' => 1
		),
	),
	'color' => array(
		'title' => esc_html__( 'Color', 'better' ),
		'type' => 'color',
	),
	'spin' => array(
		'title' => esc_html__( 'Spin', 'better' ),
		'type' => 'yes_no_button',
		'default' => false
	),
	'rotate' => array(
		'title' => esc_html__( 'Rotate', 'better' ),
		'type' => 'select',
		'options' => array(
			'' => '',
			'90' => esc_html__( '90 degrees', 'better' ),
			'180' => esc_html__( '180 degrees', 'better' ),
			'270' => esc_html__( '270 degrees', 'better' )
		)
	),
	'flip' => array(
		'title' => esc_html__( 'Flip', 'better' ),
		'type' => 'select',
		'options' => array(
			'' => '',
			'horizontal' => esc_html__( 'Horizontal', 'better' ),
			'vertical' => esc_html__( 'Vertical', 'better' )
		)
	),
	'stack_source' => array(
		'title' => esc_html__( 'Stack Source', 'better' ),
		'type' => 'icon_source',
		'default' => 'font-awesome'
	),
	'stack_type' => array(
		'title' => esc_html__( 'Stack Icon', 'better' ),
		'type' => 'icon',
	),
	'stack_color' => array(
		'title' => esc_html__( 'Stack Color', 'better' ),
		'type' => 'color',
	),
);