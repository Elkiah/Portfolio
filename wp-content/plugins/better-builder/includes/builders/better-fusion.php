<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterB_Fusion extends Better_Builder {
    // See the following for an example:
    // https://github.com/Theme-Fusion/Fusion-Builder-Sample-Add-On

	function __construct() {
        if ( is_plugin_active( 'fusion-builder/fusion-builder.php' ) && is_admin() ) {
        	add_action( 'fusion_builder_before_init', array( $this, 'map_shortcodes' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );
        }
	}

	public function builder_admin_scripts_hook() {
		wp_enqueue_script( 'better.stock-image', BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.js', array( 'jquery', 'underscore' ), '1.0.0' );
	}

    public function admin_enqueue_styles() {
        wp_register_style( 'better.fusion', BetterCore()->assets_url . 'css/fusion/fusion.min.css', null, BetterCore()->_version );
        wp_enqueue_style( 'better.fusion' );
    }

	public function map_shortcodes() {

		require_once( 'fusion/fields.php' );

		foreach ( BetterCore()->shortcodes->list as $key => $shortcode ) {
			$shortcode->map( $this );
		}

	}

	public function map_shortcode( $shortcode, $settings ) {
		$fb_mapping = array(
            'name' => $settings['name'],
			'shortcode' => $settings['shortcode'],
			'admin_enqueue_js' => $settings['fs_admin_enqueue_js'],
			//'multi' => ( isset( $settings['container'] ) && $settings['container'] == true ) ? 'multi_element_parent' : false,
            //'icon' => $this->get_icon_url(),
            'icon' => 'betterbuilder-icon',
            //'icon' => 'fusiona-font',
            'params' => array()
        );

        if ( !empty( $settings['content'] ) ) {
            if ( !isset( $settings['content_description'] ) ) {
                $settings['content_description'] = '';
            }

            $fb_mapping[ 'params' ][] = $this->map_content( $settings['content'], $settings['content_description'] );
        }

        foreach ( $settings['fields'] as $key => $field ) {
            $fb_mapping[ 'params' ][] = $this->map_field( $key, $field );
        }

		$fb_mapping[ 'params' ][] = array(
            'type' => 'textfield',
            'heading' => __( 'Element ID', 'better' ),
            'description' => __( 'Make sure it is unique and valid according to html standards', 'better' ),
            'param_name' => 'id',
        );

		$fb_mapping[ 'params' ][] = array(
            'type' => 'textfield',
            'heading' => __( 'Extra Class', 'better' ),
            'description' => __( 'Extra CSS class name', 'better' ),
            'param_name' => 'class',
        );

		// if ( isset( $fb_mapping[ "multi" ] ) && $fb_mapping[ "multi" ] == true ) {
		// 	$fb_mapping[ "element_child" ] = '';
		// }

        fusion_builder_map( $fb_mapping );

		return $shortcode;
	}

    public function map_content( $content, $content_description = '' ) {
            $content_type = 'textfield';

            switch ( $content ) {
                case 'textarea':
                    $content_type = 'textarea';
                    break;

                case 'html':
                    $content_type = 'tinymce';
                    break;

                default:

                    break;
            }

            return array(
                'type' => $content_type,
                'heading' => __( 'Content', 'better' ),
                'param_name' => 'element_content',
                'description' => $content_description,
                'value' => '',
            );
    }

    public function map_field( $key, $field ) {

        // See the following:
        // https://theme-fusion.com/support/documentation/fusion-builder-api-documentation/

        $param =  array(
            'type' => 'textfield',
            'heading' => !empty($field['title']) ? $field['title'] : '',
            'param_name' => $key,
            'value' => !empty( $field['default'] ) ? $field['default'] : '',
            'description' => ( isset( $field['description'] ) ? $field['description'] : null ),
        );

        $type = 'textfield';

		// Icon source input field option
		$icon_sources = array();
		$packs = BetterSC_Icon::get_icon_sources();
		if ( is_array( $packs ) ) {
		    foreach ($packs as $key => $pack) {
				$icon_sources[ $key ] = $pack['title'] . ' (' . $pack['count'] . ')';
		    }
		}

		// Post types
		$post_type_opts = array();
		$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
		if ( is_array( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				if ( $post_type != 'attachment' && $post_type != 'product_variation' && $post_type != 'shop_coupon' ) {
					$type = get_post_type_object( $post_type );
					$post_type_opts[ $post_type ] = $type->label;
				}
			}
		}

		// Taxonomies
		$taxonomy_opts = array();
		$taxonomy_names = get_object_taxonomies( 'post' );
		if ( is_array( $taxonomy_names ) ) {
			foreach ( $taxonomy_names as $taxonomy_name ) {
				$tax_obj = get_taxonomy( $taxonomy_name );
				$taxonomy_opts[ $taxonomy_name ] = $tax_obj->label;
			}
		}

		// Terms
		$term_opts = array();
		$categories = get_terms( 'category' );
		if ( is_array( $categories ) ) {
			foreach ( $categories as $post_cat ) {
				$term_opts[ $post_cat->slug ] = $post_cat->name;
			}
		}

        switch ( $field['type'] ) {
            case 'text':
                $param['type'] = 'textfield';
                break;

            case 'textarea':
                $param['type'] = 'textarea';
                break;

            case 'dropdown':
            case 'select':
                $param['type'] = 'select';
                $param['value'] = ( isset( $field['options'] ) ? $field['options'] : null );
                break;

            case 'color':
                $param['type'] = 'colorpickeralpha';
                break;

            case 'spacing':
                $param['type'] = 'dimension';
                break;

			case 'template':
                $param['type'] = 'select';
                $param['value'] = better_locate_available_plugin_templates( '/template/' );
                break;

            case 'cpt_template':
                $param['type'] = 'better_cpt_template';
                break;

			case 'icon_source':
                $param['type'] = 'fusion_better_icon_source';
                $param['value'] = $icon_sources;
                break;

			case 'icon':
                $param['type'] = 'fusion_better_icon';
				break;

			case 'post_type':
				$param['type'] = 'better_post_type';
				$param['value'] = $post_type_opts;
				break;

			case 'taxonomy':
				$param['type'] = 'better_taxonomy';
				$param['value'] = $taxonomy_opts;
				break;

			case 'terms':
				$param['type'] = 'better_terms';
				$param['value'] = $term_opts;
				break;
				
			// Image Stock Field
			case 'stock_image':
				$param['type'] = 'better_stock_image';
				break;
				
			case 'stock_search':
				$param['type'] = 'better_stock_search';
                break;

            case 'spacing':
                $param['type'] = 'dimension';
                break;

            case 'checkbox':
                $param['type'] = 'select';
                $param['value'] = $param['value'] ? array( 'true' => __( 'Yes' ), 'false' => __( 'No' ) ) : array( 'false' => __( 'No' ), 'true' => __( 'Yes' ) );
                $param['default'] = !empty( $field['default'] ) ? $field['default'] : '';
                break;

            case 'yes_no_button':
                $param['type'] = 'radio_button_set';
                $param['value'] = array( 'true' => __( 'Yes', 'better' ), 'false' => __( 'No', 'better' ) );
                $param['default'] = !empty( $field['default'] ) ? $field['default'] : 'false';
                break;

            case 'image':
                $param['type'] = 'upload';
                break;

			case 'image_size':
                $param['type'] = 'select';
                $param['value'] = array_merge( array( '' => '' ), array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() ) );
                break;

            case 'gallery':
                $param['type'] = 'upload_images';
                break;

            case 'file':
                $param['type'] = 'fusion_better_media_upload';
                break;

            case 'link':
                $param['type'] = 'link_selector';
                break;

            case 'range':
                $param['type'] = 'range';
                $param['min'] = ( isset( $field['range_settings'] ) ? (string) $field['range_settings']['min'] : '1' );
                $param['max'] = ( isset( $field['range_settings'] ) ? (string) $field['range_settings']['max'] : '100' );
                $param['step'] = ( isset( $field['range_settings'] ) ? (string) $field['range_settings']['step'] : '1' );
                break;

            case 'editor':
            case 'heading':
                $param['type'] = 'hidden';
                break;

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
                    $pure_condition_key = str_replace( '!', '', $condition_key );
                    if ( is_array( $condition_value ) ) {
                        //$newKey = str_replace( '!', '', $condition_key ); //preg_replace( $pattern, '', $condition_key );
                        foreach ( $condition_value as $value ) {
                            $condition[] = array(
                                'element' => $pure_condition_key,
                                'value' => $value,
                                'operator' => '!='
                            );
                        }
                    } else {
                        $condition[] = array(
                            'element' => $pure_condition_key,
                            'value' => $condition_value,
                            'operator' => '!=',
                        );
                    }
                } else {
                    if ( is_array( $condition_value ) ) {
                        foreach ( $condition_value as $value ) {
                            $condition[] = array(
                                'element' => $condition_key,
                                'value' => $value,
                                'operator' => '==',
                            );
                        }
                    } else {
                        $condition[] = array(
                            'element' => $condition_key,
                            'value' => $condition_value,
                            'operator' => '==',
                        );
                    }
                }
            }
            $param[ 'dependency' ] = $condition;
            unset( $field['condition'] );
        }

        // if ( ! empty( $field['dependency'] ) ) {

        //     if ( is_array( $field['dependency']['value_not_equal_to'] ) ) {

        //         $array_dep = array();
        //         foreach ( $field['dependency']['value_not_equal_to'] as $value ) {
        //             $array_dep[] = array(
        //                 'element' => $field['dependency']['element'], 
        //                 'value' => $value,
        //                 'operator' => '!='
        //             );
        //         }

        //         $param['dependency'] = $array_dep;
        //     } else {
        //         if ( isset( $field['dependency']['value_not_equal_to'] ) ) {
        //             $field['dependency']['operator'] = '!=';
        //             $field['dependency']['value'] = $field['dependency']['value_not_equal_to'];
        //         } else {
        //             $field['dependency']['operator'] = '==';
        //         }
                
        //         $param['dependency'][] = $field['dependency'];
        //     }

        //     // if ( ! is_array( $field['dependency']['value'] ) ) {
        //     //     $param['dependency'][] = $field['dependency'];
        //     // }

        //     //$param['dependency']['element'] = $param['dependency']['field'];
        // }

        if ( ! empty( $field['group'] ) ) {
            $param['group'] = $field['group'];
        }

        // $pattern = '/([a-z_0-9]+)(?:\[([a-z_]+)])?(!?)$/i';
        // $str = "link_style!";
        // preg_match($pattern, $str, $match);
        // //$yourWord = str_replace("!", "", $match[0]); //prints k-on
        // print_r($match);

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
