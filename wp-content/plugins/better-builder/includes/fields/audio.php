<?php

$this->fields = array(
	'track' => array(
		'title' => esc_html__( 'Track', 'better' ),
		'type' => 'select',
		'default' => '',
		'options' => array_merge( array( '' => 'Select Audio Track' ), BetterCore()->custom_post_types->get_posts_list( 'better_audio' ) )
	),
	'album' => array(
		'title' => esc_html__( 'Album slug', 'better' ),
		'type' => 'array',
	),
	'player' => array(
		'title' => esc_html__( 'Player type', 'better' ),
		'type' => 'select',
		'options' => array(
			'single'  => esc_html__( 'Single', 'better' ),
			'multiple' => esc_html__( 'Multiple', 'better' ),
			'flat-black' => esc_html__( 'Flat Black', 'better' ),
			'blue-playlist' => esc_html__( 'Blue Playlist', 'better' )
		)
	),
	'autoplay' => array(
		'title' => esc_html__( 'Autoplay', 'better' ),
		'type' => 'yes_no_button',
	),
);
