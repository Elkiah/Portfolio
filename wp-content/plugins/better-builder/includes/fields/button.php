<?php

$this->fields = array(
	'content' => array(
		'title' => esc_html__( 'Button Text', 'better' ),
		'type' => 'text',
		'js_composer' => false,
		'description' => esc_html__( 'Input your desired button text.', 'better' ),
		'default' => esc_html__( 'Button text...', 'better' ),
	),
	'button_style' => array(
		'type' => 'select',
		'title' =>  esc_html__( 'Button Style', 'better' ),
		'default' => 'flat',
		'options' => array(
			'flat' => esc_html__( 'Flat', 'better' ),
			'outline' => esc_html__( 'Outline', 'better' ),			
			'gradient' => esc_html__( 'Gradient', 'better' ),
		),
	),
	'url' => array(
		'type' => 'link',
		'title' =>  esc_html__( 'Button URL (Link)', 'better' ),			
	),
	'align' => array(
		'title' => esc_html__( 'Button Alignment', 'better' ),
		'type' => 'select',
		'options' => array(
			'left' => esc_html__( 'Left', 'better' ),
			'right' => esc_html__( 'Right', 'better' ),
			'center' => esc_html__( 'Center', 'better' ),
		),
		'description' => esc_html__( 'Here you can define the alignment of Button', 'better' ),
	),
	'target' => array(
		'type' => 'select',
		'title' =>  esc_html__( 'Link Target', 'better' ),
		'options' => array(
			'_self' => esc_html__( 'Same Window', 'better' ),
			'_blank' => esc_html__( 'New Window', 'better' ),
		),
	),
	'rel' => array(
		'type' => 'text',
		'title' =>  esc_html__( 'Link rel', 'better' ),				
	),
	'font_size' => array(
		'type' => 'range',
		'title' =>  esc_html__( 'Font Size', 'better' ),
		'default' => 14,
		'range_settings' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		)
	),
	'border_radius' => array(
		'title' =>  esc_html__( 'Border Radius', 'better' ),
		'type' => 'range',
		'default' => 30,
		'range_settings' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		)
	),
	'padding_tb' => array(
		'title' =>  esc_html__( 'Top and Bottom Padding', 'better' ),
		'type' => 'range',
		'default' => 17,
		'range_settings' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		)
	),
	'padding_lr' => array(
		'title' =>  esc_html__( 'Left and Right Padding', 'better' ),
		'type' => 'range',
		'default' => 30,
		'range_settings' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		)
	),
	'text_color' => array(
		'type' => 'color',
		'title' =>  esc_html__( 'Text Color', 'better' ),
		'group' => esc_html__( 'Color', 'better' ),
	),
	'background_color' => array(
		'type' => 'color',
		'title' =>  esc_html__( 'Background Color', 'better' ),
		'condition' => array(
			'button_style' => 'flat',
		),
		'group' => esc_html__( 'Color', 'better' ),
	),
	'border_color' => array(
		'title' =>  esc_html__( 'Border Color', 'better' ),
		'type' => 'color',
		'condition' => array(
			'button_style' => 'outline',
		),
		'group' => esc_html__( 'Color', 'better' ),
	),
	'border_width' => array(
		'title' =>  esc_html__( 'Border Width', 'better' ),
		'type' => 'range',
		'default' => 2,
		'range_settings' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		),
		'condition' => array(
			'button_style' => 'outline',
		),
		'group' => esc_html__( 'Color', 'better' ),
	),
	'background_gradient_start' => array(
		'title' =>  esc_html__( 'Gradient Start Color', 'better' ),
		'type' => 'color',
		'condition' => array(
			'button_style' => 'gradient',
		),
		'group' => esc_html__( 'Color', 'better' ),
	),
	'background_gradient_end' => array(
		'title' =>  esc_html__( 'Gradient End Color', 'better' ),
		'type' => 'color',
		'condition' => array(
			'button_style' => 'gradient',
		),
		'group' => esc_html__( 'Color', 'better' ),
	),
	// Hover color group
	'text_color_hover' => array(
		'type' => 'color',
		'title' =>  esc_html__( 'Text Color Hover', 'better' ),
		'group' => esc_html__( 'Hover Color', 'better' )
	),
	'background_color_hover' => array(
		'type' => 'color',
		'style' => true,
		'title' =>  esc_html__( 'Background Color Hover', 'better' ),
		'condition' => array(
			'button_style' => 'flat'
		),
		'group' => esc_html__( 'Hover Color', 'better' )
	),
	'border_color_hover' => array(
		'type' => 'color',
		'style' => true,
		'title' =>  esc_html__( 'Border Color Hover', 'better' ),
		'condition' => array(
			'button_style' => 'outline'
		),
		'group' => esc_html__( 'Hover Color', 'better' )
	),
	'background_gradient_start_hover' => array(
		'type' => 'color',
		'style' => true,
		'title' =>  esc_html__( 'Gradient Start Color Hover', 'better' ),
		'condition' => array(
			'button_style' => 'gradient'
		),
		'group' => esc_html__( 'Hover Color', 'better' )
	),
	'background_gradient_end_hover' => array(
		'type' => 'color',
		'title' =>  esc_html__( 'Gradient End Color Hover', 'better' ),
		'condition' => array(
			'button_style' => 'gradient'
		),
		'group' => esc_html__( 'Hover Color', 'better' )
	),
);
