<?php

$this->fields = array(
	'type' => array(
		'type' => 'select',
		'title' => esc_html__( 'Type', 'better' ),
		'options' => \BetterSC_Divider::separator_types(),
	),
	'location' => array(
		'type' => 'select',
		'title' => esc_html__( 'Location', 'better' ),
		'options' => array(
			'top' => esc_html__( 'Top', 'better' ),
			'bottom' => esc_html__( 'Bottom', 'better' ),
		),
	),
	'primary_color' => array(
		'title' => esc_html__( 'Primary Color', 'better' ),
		'type' => 'color',
	),
	'secondary_color' => array(
		'title' => esc_html__( 'Secondary Color', 'better' ),
		'type' => 'color',
		'description' => esc_html__( 'may not apply to most divider types', 'better' ),
	),
	'tertiary_color' => array(
		'title' => esc_html__( 'Tertiary Color', 'better' ),
		'type' => 'color',
		'description' => esc_html__( 'may not apply to most divider types', 'better' ),
	),
);