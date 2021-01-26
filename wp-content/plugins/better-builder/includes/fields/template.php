<?php

$this->fields = [
	'path' => [
		'title' => esc_html__( 'Path', 'better' ),
		'type' => 'select',
		'default' => 'blank',
		//'class' => 'template',
		'options' => better_locate_available_plugin_templates( '/template/' ),
		'description' => esc_html__( 'Place the template files inside a betterbuilder/templates/template/ folder in your theme or child theme.', 'better' ),
	]
];