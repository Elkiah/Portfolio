<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterB_VC extends Better_Builder {

	function __construct() {
		// Added uncode-js_composer check below for Uncode's version of VC (in Uncode's theme)
		// Added js_composer_salient check below for Salient Visual Composer (in Salient theme)
        if ( ( is_plugin_active( 'js_composer/js_composer.php' ) || is_plugin_active( 'js_composer_theme/js_composer.php' ) || is_plugin_active( 'uncode-js_composer/js_composer.php' ) || is_plugin_active( 'js_composer_salient/js_composer.php' ) ) ) {
            if ( defined ( 'WPB_VC_VERSION' ) && version_compare( WPB_VC_VERSION, '4.3.2', '>=' ) ) {
                add_action( 'vc_before_init', array( $this, 'map_shortcodes' ) );
            } else if ( is_admin() ) {
                add_action( 'after_setup_theme', array( $this, 'map_shortcodes' ) );
            }

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );
        }
	}

    public function admin_enqueue_styles() {
        wp_register_style( 'better.visual-composer', BetterCore()->assets_url . '/css/visual-composer/visual-composer.min.css', null, BetterCore()->_version );
        wp_enqueue_style( 'better.visual-composer' );
    }

	public function map_shortcodes() {

        require_once( 'visual-composer/fields.php' );

		foreach ( BetterCore()->shortcodes->list as $key => $shortcode ) {

			$shortcode->map( $this );

		}

	}

	public function map_shortcode( $shortcode, $settings ) {

		$vc_mapping = array(
            'name' => $settings['name'],
            'base' => $settings['shortcode'],//strtolower( get_class( $this ) ),
            //'class' => "betterbuilder_wpb",
            //'svg-icon' => $this->get_icon(),
            //'icon' => $this->get_icon_url(),
            'icon' => 'better-builder',
            'category' => __( "Better Builder", 'better' ),
			'is_container' => !empty($settings['container']),
            'params' => array()
        );

        $settings['fields'] = array_merge( $settings['fields'], array(
            'id' => array(
                'type' => 'text',
                'title' => __( 'Element ID', 'better' ),
                'description' => __( 'Make sure it is unique and valid according to html standards', 'better' ),
            ),
            'class' => array(
                'type' => 'text',
                'title' => __( 'Extra class', 'better' ),
                'description' => __( 'Extra CSS class name', 'better' ),
            ),
        ));

        if ( !empty( $settings['content'] ) ) {
            if ( !isset( $settings['content_description'] ) ) {
                $settings['content_description'] = '';
            }

            $vc_mapping[ 'params' ][] = $this->map_content( $settings['content'], $settings['content_description'] );
            $vc_mapping['content_element'] = true;
        }

        foreach ( $settings['fields'] as $key => $field ) {
            $vc_mapping[ 'params' ][] = $this->map_field( $key, $field );
        }

		if ( isset( $vc_mapping[ "is_container" ] ) && $vc_mapping[ "is_container" ] == true ) {
			if ( !class_exists( "WPBakeryShortCode_" . $settings['shortcode'] ) ) {
                eval( "class WPBakeryShortCode_" . $settings['shortcode'] . " extends WPBakeryShortCodesContainer {

                };" );
            }
			$vc_mapping[ "js_view" ] = 'VcColumnView';
			$vc_mapping[ "as_parent" ] = array( 'except' => $settings['shortcode'] );
		}

        vc_map( $vc_mapping );

		return $shortcode;

	}

    public function map_content( $content, $description = '' ) {
            $content_type = 'textfield';

            switch ( $content ) {
                case 'textarea':
                    $content_type = 'textarea';
                    break;

                case 'html':
                    $content_type = 'textarea_html';
                    break;

                default:

                    break;
            }

            return array(
                'type' => $content_type,
                'holder' => 'div',
                'heading' => __( 'Content', 'better' ),
                'param_name' => 'content',
                'description' => $description,
                'value' => ''
            );
    }

    public function map_field( $key, $field ) {
        // See the following:
        // https://wpbakery.atlassian.net/wiki/spaces/VC/pages/524332/vc+map#vc_map()-Parameters

        $param =  array(
            'type' => 'textfield',
            //'holder' => 'button',
            'class' => !empty( $field['class'] ) ? $field['class'] : '',
            'heading' => !empty($field['title']) ? $field['title'] : '',
            'param_name' => $key,
            'value' => !empty( $field['default'] ) ? $field['default'] : '',
            'description' => ( isset( $field['description'] ) ? $field['description'] : null ),
			//'admin_label' => $this->composer_show_value,
        );
        
        $cpt_options = array();
		$cpt_templates = better_locate_available_plugin_templates( '/custom-post/post/' );
		foreach( $cpt_templates as $key => $cpt_template ) {
			$cpt_options[ $key ] = $cpt_template;
		}

        switch ( $field['type'] ) {
			case 'text':
                $param['type'] = 'textfield';
				break;

            case 'dropdown':
            case 'select':
                $param['type'] = 'dropdown';
                $param['value'] = ( isset( $field['options'] ) ? $this->array_swap_assoc( $field['options'] ) : null );
                break;

            case 'color':
                $param['type'] = 'colorpicker';
                break;

            case 'spacing':
                $param['type'] = 'better_spacing';
                break;

			case 'template':
                $param['type'] = 'better_template';
                break;

			case 'cpt_template':
                $param['type'] = 'dropdown';
                $param['value'] = $cpt_options;
                break;

			// Better Icon Selector Field
			case 'icon_source':
                $param['type'] = 'better_icon_source';
                break;

			case 'icon':
                $param['type'] = 'better_icon';
				break;

			case 'post_type':
				$param['type'] = 'better_post_type';
				break;

			case 'taxonomy':
				$param['type'] = 'better_taxonomy';
				break;

			case 'terms':
				$param['type'] = 'better_terms';
				break;
			
			// Image Stock Field
			case 'stock_image':
				$param['type'] = 'better_stock_image';
				break;
				
			case 'stock_search':
				$param['type'] = 'better_stock_search';
                break;

            case 'checkbox':
            case 'yes_no_button':
                $param['type'] = 'dropdown';
                $param['value'] = $param['value'] ? array( __( 'Yes' ) => 'true', __( 'No' ) => 'false' ) : array( __( 'No' ) => 'false', __( 'Yes' ) => 'true' );
                break;

            case 'image':
                $param['type'] = 'attach_image';
                break;

			case 'image_size':
                $param['type'] = 'better_image_size';
                break;

            case 'spacing':
                $param['type'] = 'better_spacing';
                break;

            case 'gallery':
                $param['type'] = 'attach_images';
                break;

            case 'file':
                $param['type'] = 'better_media_upload';
                break;

            case 'link':
                $param['type'] = 'vc_link';
                break;

            case 'editor':
                $param['type'] = 'hidden';
                break;

            case 'heading':
                $param['type'] = 'better_heading';
                $param['edit_field_class'] = 'better-param-heading-wrapper vc_column vc_col-sm-12';
                break;

            case 'range':
                $param['type'] = 'better_range';
                $param['min'] = ( isset( $field['range_settings'] ) ? $field['range_settings']['min'] : 0 );
                $param['max'] = ( isset( $field['range_settings'] ) ? $field['range_settings']['max'] : 100 );
                $param['step'] = ( isset( $field['range_settings'] ) ? $field['range_settings']['step'] : 1 );
                break;

            // case 'font_family':
            //     $param['type'] = 'google_fonts';
            //     $param['heading'] = '';
            //     $param['value'] = 'Abril%20Fatface%3Aregular';
            //     $param['settings'] = array(
			// 		'fields' => array(
			// 			'font_family_description' => __( 'Select font family.', 'js_composer' ),
			// 		),
			// 	);
			// 	break;

            default:
                # code...
                break;
        }

        if ( isset( $field['js_composer'] ) ) {
            if ( $field['js_composer'] === false ) {
                $param['type'] = 'hidden';
            }
        }

        if ( ! empty( $field['condition'] ) ) {
            //$pattern = '/([a-z_0-9]+)(?:\[([a-z_]+)])?(!?)$/i';
            $condition = array();
            foreach ( $field['condition'] as $condition_key => $condition_value ) {
                if ( strpos( $condition_key, '!' ) !== false ) {
                    //$newKey = str_replace( '!', '', $condition_key ); //preg_replace( $pattern, '', $condition_key );
                    $condition = array(
                        'element' => str_replace( '!', '', $condition_key ),
                        'value_not_equal_to' => $condition_value
                    );
                } else {
                    $condition = array(
                        'element' => $condition_key,
                        'value' => $condition_value
                    );
                }
            }
            $param[ 'dependency' ] = $condition;
            unset( $field['condition'] );
        }

        // if ( ! empty( $field['condition'] ) ) {
        //     $param['dependency'] = $field['condition'];
        //     //$param['dependency']['element'] = $param['dependency']['field'];
        // }

        if ( !empty( $field['group'] ) ) {
            $param['group'] = $field['group'];
        }

        return $param;
    }

    private function array_swap_assoc($array) {

      $newArray = array ();

      foreach ($array as $key => $value) {

        if ( !is_array( $value ) ) {
            $newArray[ $value ] = $key;
        }

      }

      return $newArray;

    }

}
