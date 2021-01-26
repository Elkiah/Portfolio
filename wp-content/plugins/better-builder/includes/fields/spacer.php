<?php

$this->fields = [
	'height' => [
		'title' => __( 'Height', 'better' ),
		'type' => 'range',
		'range_settings' => [
			'min' => 0,
			'max' => 1000
		],
		'default' => 32,
		'selectors' => [
			'{{WRAPPER}} .better-spacer' => 'height: {{SIZE}}{{UNIT}};',
		],
	]
];