<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Better_Shortcode {

	protected $fields = array();

	public function get_fields() {
		if ( empty( $this->fields ) ) {
			if ( false === ( $fields = wp_cache_get( 'better_fields_' . get_class( $this ) ) ) ) {
				$field_file_path = BetterCore()->dir . '/includes/fields/' . str_replace( '_', '-', str_replace( 'bettersc_', '', strtolower( get_class( $this ) ) ) )  . '.php';

				if ( file_exists( $field_file_path ) ) {
					require $field_file_path;
				}

				$fields = $this->fields;

				wp_cache_set( 'better_fields_' . get_class( $this ), $fields );
			}

			unset($this->fields);
		} else {
			$fields = $this->fields;
		}

		return $fields;
	}

	public function register_assets() {

	}

	public function render_shortcode( $atts, $content = '' ) {
		$shortcode = $this->shortcode_name();
		$att_map = '';

		foreach ( $atts as $key => $value ) {
			if ( !is_array( $value ) ) {
				$att_map .= "$key=\"$value\" ";
			} else {
				$arr_val = implode( ',', $value );
				$att_map .= "$key=\"$arr_val\" ";
			}
		}

		if ( ! empty( $content ) ) {
			return do_shortcode( '[' . $shortcode . ' ' . $att_map . ']' . $content . '[/' . $shortcode . ']' );
		} else {
			return do_shortcode( '[' . $shortcode . ' ' . $att_map . ' ]' );
		}
	}

	public function build( $atts, $content = '' ) {

		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $value) {

	    		if ( $value == 'false' ) {
	    			$atts[ $key ] = false;
	    		}

	    		if ( $value == 'true' ) {
	    			$atts[ $key ] = true;
	    		}

	    	}
	    }

    	$original_atts = $atts;

		$atts = shortcode_atts( $this->shortcode_atts( $atts ), $atts );

		if ( is_array( $original_atts ) ) {
			$atts = array_merge( $atts, $original_atts );
		}

		$obj = new ReflectionClass($this);
    	$filename = basename( $obj->getFileName(), '.php' );

    	if ( empty( $atts['id'] ) ) $atts['id'] = get_class( $this ) . '_' . rand();

    	$atts['content'] = do_shortcode( $this->shortcode_unautop( $content ) );
    	$atts['style'] = $this->atts_style( $atts );
    	$atts['id_attr'] = 'id="' . esc_attr( $atts['id'] ) . '"';
    	$atts['styles'] = array();

    	if ( !empty( $atts['hidden_lg'] ) ) $atts['class'] .= ' hidden-lg';
    	if ( !empty( $atts['hidden_md'] ) ) $atts['class'] .= ' hidden-md';
    	if ( !empty( $atts['hidden_sm'] ) ) $atts['class'] .= ' hidden-sm';
			if ( !empty( $atts['hidden_xs'] ) ) $atts['class'] .= ' hidden-xs';
			
			if ( !empty( $atts['classname'] ) ) $atts['class'] = trim( $atts['classname'] );

    	$atts['class'] = trim( $atts['class'] );

			$margin_unit = ( !empty( $atts['marginunit'] ) ) ? $atts['marginunit'] : 'px';
			if ( !empty( $atts['margintop'] ) ) $atts['margin'] .= $atts['margintop'] . $margin_unit . ' ';
    	if ( !empty( $atts['marginright'] ) ) $atts['margin'] .= $atts['marginright'] . $margin_unit . ' ';
    	if ( !empty( $atts['marginbottom'] ) ) $atts['margin'] .= $atts['marginbottom'] . $margin_unit . ' ';
			if ( !empty( $atts['marginleft'] ) ) $atts['margin'] .= $atts['marginleft'] . $margin_unit;

    	$css = !empty( $atts['style'] ) ? '#' . $atts['id'] . ' { ' . $atts['style'] . '}' : '';

    	$atts['atts'] = $atts;

		return BetterCore()->do_inline_css( $css ) . BetterCore()->template( 'shortcodes/' . $filename, null, $atts, false );

	}

	public function shortcode_atts( $atts ) {
		return $this->map( $this, 'map_shortcode_defaults', $atts );
	}

	public function atts_style( $atts ) {
		return $this->map( $this, 'map_shortcode_styles', $atts );
	}

	public function shortcode_name( ) {
		return $this->map( $this, 'map_shortcode_name' );
	}

	public function map_shortcode_name( $shortcode, $settings, $atts ) {
		return $settings['shortcode'];
	}

	public function map_shortcode_styles( $shortcode, $settings, $atts ) {

		$style = '';

		foreach ( $settings['fields'] as $key => $field ) {

			if ( !empty( $field['style'] ) && !empty( $atts[ $key ] ) ) {

				$style .= str_replace( '_', '-', $key ) . ': ' . $atts[ $key ] . '; ';

			}

		}

		return $style;

	}

	public function map_shortcode_defaults( $shortcode, $settings, $atts ) {

		$defaults = array();

		$settings['fields'] = array_merge( $settings['fields'], array(
            'id' => array( 'type' => 'text', ),
            'class' => array( 'type' => 'text', ),
        ));

		foreach ( $settings['fields'] as $key => $field ) {

			$defaults[ $key ] = '';

			if ( isset( $field['default'] ) ) {

				$defaults[ $key ] = $field['default'];

			}

		}

		if ( empty( $defaults['id'] ) ) {

			$defaults[ 'id' ] = $settings['shortcode'] . '_' . rand();

    	}

		return $defaults;

	}

	public function map_shortcode( $shortcode, $settings ) {

		add_shortcode( $settings['shortcode'], array( $shortcode, 'build' ) );

		return $shortcode;

	}

	public static function css_animation() {
		return array(
			'fade-in' 			=> esc_html__( 'Fade In', 'better' ),
			'move-up' 			=> esc_html__( 'Move Up', 'better' ),
			'move-down' 		=> esc_html__( 'Move Down', 'better' ),
			'move-left' 		=> esc_html__( 'Move Left', 'better' ),
			'move-right' 		=> esc_html__( 'Move Right', 'better' ),
			'scale-up' 			=> esc_html__( 'Scale Up', 'better' ),
			'fall-perspective' 	=> esc_html__( 'Fall Perspective', 'better' ),
			'fly' 				=> esc_html__( 'Fly', 'better' ),
			'flip' 				=> esc_html__( 'Flip', 'better' ),
			'helix' 			=> esc_html__( 'Helix', 'better' ),
			'pop-up' 			=> esc_html__( 'Pop Up', 'better' ),
		);
	}

	function shortcode_unautop( $content ) {
		global $shortcode_tags;

		if ( empty( $shortcode_tags ) || !is_array( $shortcode_tags ) ) {
			return $content;
		}

		$tagregexp = join( '|', array_map( 'preg_quote', array_keys( $shortcode_tags ) ) );
		$spaces = wp_spaces_regexp();

		$pattern =
			  '/'
			. '<p>'                              // Opening paragraph
			. '(?:' . $spaces . ')*+'            // Optional leading whitespace
			. '('                                // 1: The shortcode
			.     '\\['                          // Opening bracket
			.     "($tagregexp)"                 // 2: Shortcode name
			.     '(?![\\w-])'                   // Not followed by word character or hyphen
			                                     // Unroll the loop: Inside the opening shortcode tag
			.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			.     '(?:'
			.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
			.     ')*?'
			.     '(?:'
			.         '\\/\\]'                   // Self closing tag and closing bracket
			.     '|'
			.         '\\]'                      // Closing bracket
			.         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			.             '[^\\[]*+'             // Not an opening bracket
			.             '(?:'
			.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			.                 '[^\\[]*+'         // Not an opening bracket
			.             ')*+'
			.             '\\[\\/\\2\\]'         // Closing shortcode tag
			.         ')?'
			.     ')'
			. ')'
			. '(?:' . $spaces . ')*+'            // optional trailing whitespace
			. '<\\/p>'                           // closing paragraph
			. '/';

		return preg_replace( $pattern, '$1', $content );
	}
}
