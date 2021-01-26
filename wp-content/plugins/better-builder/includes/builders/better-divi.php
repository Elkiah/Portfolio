<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterB_Divi extends Better_Builder {
    // See the following for an example:

	// Divi Theme/Builder 2.0.+
    // https://divi.space/blog/adding-custom-modules-to-divi/
    // https://jonathanbossenger.com/building-your-own-divi-builder-modules/

	// Divi Theme/Builder 3.0
    // https://divi.space/blog/adding-custom-modules-to-divi/

	function __construct() {
		$theme = wp_get_theme(); // gets the current theme
		if ( ( is_plugin_active( 'DiviBuilder/divi-builder.php' ) || 'Divi' == $theme->name || 'Divi' == $theme->parent_theme ) ) {
            add_action( 'et_builder_ready', array( $this, 'better_divi_setup' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );
		}
	}

	public function admin_enqueue_styles() {
        wp_enqueue_style( 'better.divi', BetterCore()->assets_url . 'css/divi/divi.min.css', null, BetterCore()->_version );
		wp_enqueue_script( 'better.stock-image' );
    }

    public function better_divi_setup() {

		if ( ! class_exists( 'ET_Builder_Module' ) ) { return; }

		require __DIR__ . '/divi/elements.php';
        
        // disable box shortcode on divi builder
        if ( function_exists( 'is_gutenberg' ) && ! is_gutenberg() ) {
			unset(BetterCore()->shortcodes->list['BetterSC_Box']);
		}

		foreach ( BetterCore()->shortcodes->list as $key => $shortcode ) {

			$divi_modules_name = str_replace( 'BetterSC_', 'BetterDivi_Module_', $key );

			if ( ! class_exists( $divi_modules_name ) ) {
                eval( 'class ' . $divi_modules_name . ' extends Better_Divi_Module {
				};' );
            }

			if ( class_exists( $divi_modules_name ) ) {
				new $divi_modules_name;
            }
            
		}

	}

	public function map_fields( $shortcode, $settings ) {
        $fields = array();

        foreach ( $settings['fields'] as $key => $field ) {
            $fields[ $key ] = $this->map_field( $key, $field );
        }

        return $fields;
	}
	
	public function map_field( $key, $field ) {
        $param = array();

        if ( isset( $field['default'] ) ) {
            $param['default'] = $field['default'];
        }

        switch ( $field['type'] ) {
            case 'text':
            case 'post_type':
            case 'taxonomy':
                $param['type'] = 'string';
				break;
				
			case 'array':
            case 'terms':
                $param['type'] = 'array';
                break;

            case 'textarea':
                $param['type'] = 'string';
                break;

            case 'dropdown':
                $param['type'] = 'string';
				break;
				
			case 'template':
				$param['type'] = 'string';
				break;

			case 'number':
			case 'range':
                $param['type'] = 'number';
                break;

            case 'color':
                $param['type'] = 'string';
                break;

            case 'font_family':
                $param['type'] = 'string';
                break;

            case 'spacing':
                $param['type'] = 'string';
                break;

            case 'checkbox':
                $param['type'] = 'boolean';
                break;

            case 'image':
                $param['type'] = 'number';
                break;

            case 'gallery':
                $param['type'] = 'string';
                break;

            case 'file':
                $param['type'] = 'string';
                break;

            default:
                # code...
                break;
        }

        return $param;
    }

}
