<?php

$this->fields = array(
	'paragraphs' => array(
		'title' => esc_html__( 'Paragraphs', 'better' ),
		'type' => 'text',
	),
	'words' => array(
		'title' => esc_html__( 'Words', 'better' ),
		'type' => 'text',
	),
	'bytes' => array(
		'title' => esc_html__( 'Bytes', 'better' ),
		'type' => 'text',
	),
	'list' => array(
		'title' => esc_html__( 'List Items', 'better' ),
		'type' => 'text',
	),
	'paragraph_separator' => array(
		'title' => esc_html__( 'Paragraph Separator', 'better' ),
		'type' => 'select',
		'options' => array(
			'p' => esc_html__( 'Paragraph', 'better' ),
			'br' => esc_html__( 'Break', 'better' ),			
			'span' => esc_html__( 'Span', 'better' ),
		),
	),
	'start_with_lorem' => array(
		'title' => esc_html__( 'Start with Lorem', 'better' ),
		'type' => 'yes_no_button',
	),
	'is_title' => array(
		'title' => esc_html__( 'Is Title', 'better' ),
		'type' => 'yes_no_button',
		'description' => esc_html__( 'indicates that the text should have the first character of each word uppercased', 'better' ),
	),
);
