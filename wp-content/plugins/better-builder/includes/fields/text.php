<?php

$this->fields = array(
	'content' => array(
		'title' => esc_html__( 'Content', 'better' ),
		'type' => 'editor',
		'default' => esc_html__( 'Add Your Text Here', 'better' ),
		'description' => esc_html__( 'Here you can create the content that will be used within the module.', 'better' )
	),
	'tag' => array(
		'title' => esc_html__( 'HTML tag', 'better' ),
		'type' => 'select',
		'default' => 'h1',
		'options' => array(
			'div' => esc_html__('div (block text)', 'better'),
			'span' => esc_html__('span (inline text)', 'better'),
			'p'  => esc_html__('p (paragraph)', 'better'),
			'h1' => esc_html__('h1 (heading 1)', 'better'),
			'h2' => esc_html__('h2 (heading 2)', 'better'),
			'h3' => esc_html__('h3 (heading 3)', 'better'),
			'h4' => esc_html__('h4 (heading 4)', 'better'),
			'h5' => esc_html__('h5 (heading 5)', 'better'),
			'h6' => esc_html__('h6 (heading 6)', 'better'),
		),
	),
	'font_family' => array(
		'title' => esc_html__( 'Font Family', 'better' ),
		'type' => 'font_family',
		'description' => esc_html__('Leave default to inherit from the selected tag', 'better'),
		'group' => 'Typography'
	),
	'font_size' => array(
		'title' => esc_html__( 'Font size', 'better' ),
		'type' => 'range',
		'range_settings' => array(
			'min' => 1,
			'max' => 200,
			'step' => 1
		),
		'group' => 'Typography'
	),
	'font_weight' => array(
		'title' => esc_html__( 'Font weight', 'better' ),
		'type' => 'select',
		'options' => array(
			'' => '',
			'lighter' => __( 'Lighter', 'better' ),
			'100' => '100',
			'200' => '200',
			'300' => '300',
			'400' => __( '400 (Normal)', 'better' ),
			'500' => '500',
			'600' => '600',
			'700' => __( '700 (Bold)', 'better' ),
			'800' => '800',
			'900' => '900',
			'bolder' => __( 'Bolder', 'better' ),
		),
		'group' => 'Typography',
	),
	'text_transform' => array(
		'title' => esc_html__( 'Text transform', 'better' ),
		'type' => 'select',
		'group' => 'Typography',
		'options' => array(
			'' => '',
			'none' => esc_html__( 'None', 'better' ),
			'capitalize' => esc_html__( 'Capitalize', 'better' ),
			'uppercase' => esc_html__( 'Uppercase', 'better' ),
			'lowercase' => esc_html__( 'Lowercase', 'better' ),
			'initial' => esc_html__( 'Initial', 'better' ),
			'inherit' => esc_html__( 'Inherit', 'better' )
		),
	),
	'text_align' => array(
		'title' => esc_html__( 'Align', 'better' ),
		'type' => 'select',
		'options' => array(
			'left' => esc_html__( 'Left', 'better' ),
			'center' => esc_html__( 'Center', 'better' ),
			'right' => esc_html__( 'Right', 'better' ),
		),
		'group' => 'Typography'
	),
	'line_height' => array(
		'title' => esc_html__( 'Line height', 'better' ),
		'type' => 'range',
		'default' => .8,
		'description' => esc_html__( 'CSS line heights', 'better' ),
		'range_settings' => array(
			'min' => 1,
			'max' => 3,
			'step' => .1
		),
		'group' => 'Typography',
	),
	'letter_spacing' => array(
		'title' => esc_html__( 'Letter spacing', 'better' ),
		'type' => 'range',
		'group' => 'Typography',
		'default' => 0,
		'description' => esc_html__( 'Spacing between individual letters', 'better' ),
		'range_settings'  => array(
			'min'  => -3,
			'max' => 3,
			'step' => .1
		),
	),
	'responsive' => array(
		'title' => esc_html__( 'Responsive', 'better' ),
		'type' => 'yes_no_button',
		'group' => 'Responsive',
	),
	'max_font_size' => array(
		'title' => esc_html__( 'Maximum Font size', 'better' ),
		'type' => 'range',
		'range_settings'  => array(
			'min'  => 1,
			'max' => 100,
			'step' => 1
		),
		'description' => esc_html__( 'Maximum responsive font size', 'better' ),
		'condition' => array(
			'responsive' => 'on'
		),
		'group' => 'Responsive',
	),
	'min_font_size' => array(
		'title' => esc_html__( 'Minimum Font size', 'better' ),
		'type' => 'range',
		'range_settings'  => array(
			'min'  => 1,
			'max' => 100,
			'step' => 1
		),
		'description' => esc_html__( 'Minimum responsive font sizes', 'better' ),
		'condition' => array(
			'responsive' => 'on'
		),
		'group' => 'Responsive',
	),
	'color' => array(
		'title' => esc_html__( 'Color', 'better' ),
		'type' => 'color',
		'group' => 'Design',
	),
	'background_color' => array(
		'title' => esc_html__( 'Background color', 'better' ),
		'type' => 'color',
		'group' => 'Design',
	),
	'padding' => array(
		'title' => esc_html__( 'Padding', 'better' ),
		'type' => 'spacing',
		'description' => esc_html__( 'Inside spacing (px, em, %)', 'better' ),
		'selectors' => array(
			'{{WRAPPER}} .better-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
		),
		'group' => 'Design',
	),
	'margin' => array(
		'title' => esc_html__( 'Margin', 'better' ),
		'type' => 'spacing',
		'description' => esc_html__( 'Outside spacing (px, em, %)', 'better' ),
		'selectors' => array(
			'{{WRAPPER}} .better-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
		),
		'group' => 'Design',
	),
	
);
