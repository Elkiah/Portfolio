<?php

$this->fields = array(
	'data' => [
		'title' => esc_html__( 'Data', 'better' ),
		'type' => 'text',
		'description' => esc_html__( 'the data to embed in the QR code. Often this is used to embed URLs', 'better' )
	],
	'width' => [
		'title' => esc_html__( 'Width', 'better' ),
		'type' => 'range',
		'description' => esc_html__( 'measured in pixels', 'better' ),
		'range_settings' => [
			'min' => 0,
			'max' => 540,
		],
		'default' => 150
	]
);