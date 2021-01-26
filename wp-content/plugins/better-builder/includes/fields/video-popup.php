<?php

$this->fields = array(
    'link_style' => [
        'title' => esc_html__( 'Link Style', 'better' ),
        'type' => 'select',
        'default' => 'play_button',
        'options' => [
            'play_button' => esc_html__('Play Button', 'better'),
            'play_button_with_text' => esc_html__('Play Button With text', 'better'),
            'play_button_2' => esc_html__('Play Button With Preview Image', 'better'),
            'better-button' => esc_html__('Better Button', 'better')
        ],
        'description' => esc_html__('Please select your link style', 'better'),
    ],
    'video_url' => [
        'title' => esc_html__( 'Video URL', 'better' ),
        'type' => 'text',
        'description' => __( 'The URL to your video on Youtube or Vimeo e.g. <br/> https://vimeo.com/63275992 <br/> https://www.youtube.com/watch?v=zDPN4EMq6rg', 'better' ),
    ],
    'play_button_color' => [
        'title' => esc_html__( 'Play Button Color', 'better' ),
        'type' => 'color',
        'default' => '#000',
        'condition' => array(
            'link_style!' => 'better-button'
        ),
        'description' => esc_html__( "Choose a color for video play button", 'better' ),
    ],
    'preview_image' => [
        'title' => esc_html__( 'Video Preview Image', 'better' ),
        'type' => 'image',
        'dynamic' => [
            'active' => true,
        ],
        'condition' => array(
            'link_style' => 'play_button_2'
        ),
        'description' => esc_html__( 'Select image from media library.', 'better' ),
    ],
    'hover_effect' => [
        'title' => esc_html__( 'Hover Effect', 'better' ),
        'type' => 'select',
        'default' => 'default',
        'options' => [
            'default' => esc_html__( 'Zoom BG Image', 'better' ), 
            'zoom_button' => esc_html__( 'Zoom Button', 'better' )
        ],
        'condition' => array(
            'link_style' => 'play_button_2'
        ),
        'description' => esc_html__( 'Select your desired hover effect', 'better' ),
    ],
    'box_shadow' => [
        'title' => esc_html__( 'Box Shadow', 'better' ),
        'type' => 'select',
        'default' => 'default',
        'options' => [
            'none' => __( 'None', 'better' ), 
            'small_depth' => esc_html__( 'Small Depth', 'better' ), 
            'medium_depth' => esc_html__( 'Medium Depth', 'better' ), 
            'large_depth' => esc_html__( 'Large Depth', 'better' ), 
            'x_large_depth' => esc_html__( 'Very Large Depth', 'better' )
        ],
        'condition' => array(
            'link_style' => 'play_button_2'
        ),
        'description' => esc_html__( 'Select your desired image box shadow', 'better' ),
    ],
    'border_radius' => [
        'title' => esc_html__( 'Border Radius', 'better' ),
        'type' => 'select',
        'default' => 'default',
        'options' => [
            'none' => esc_html__( '0px', 'better' ),
            '3px' => esc_html__( '3px', 'better' ),
            '5px' => esc_html__( '5px', 'better' ), 
            '10px' => esc_html__( '10px', 'better' ), 
            '15px' => esc_html__( '15px', 'better' ), 
            '20px' => esc_html__( '20px', 'better' )
        ],
        'condition' => array(
            'link_style!' => ['play_button', 'play_button_with_text']
        ),
    ],
    'play_button_size' => [
        'title' => esc_html__( 'Play Button Size', 'better' ),
        'type' => 'select',
        'default' => 'default',
        'options' => [
            'default' => esc_html__( 'Default', 'better' ),
            'larger' => esc_html__( 'Larger', 'better' )
        ],
        'condition' => array(
            'link_style' => 'play_button_2'
        ),
    ],
    'link_text' => [
        'title' => esc_html__( 'Link Text', 'better' ),
        'type' => 'text',
        'description' => esc_html__( 'The text that will be displayed for your link', 'better' ),
        'condition' => array(
            'link_style!' => ['play_button', 'play_button_2']
        ),
    ],
    'button_color' => [
        'title' => esc_html__( 'Color', 'better' ),
        'type' => 'color',
        'default' => '#000',
        'condition' => array(
            'link_style' => 'better-button'
        ),
        'description' => esc_html__( 'Choose a color for your button.', 'better' ),
    ]
);
