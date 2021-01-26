<?php

class Better_Divi_Module extends ET_Builder_Module {

    protected $groups = array();

    function init() {
        $name = str_replace( '_', ' ', str_replace( 'BetterDivi_Module_', '', get_class( $this ) ) );
        $slug = str_replace( 'betterdivi_module_', 'et_pb_better_', strtolower( get_class( $this ) ) );

        $this->name         = esc_html( $name );
        $this->slug         = $slug;

        $toggles = array( 'main' => esc_html( $this->name ) );
        
        foreach ( $this->map_fields() as $key => $field ) {
            if ( ! empty( $field['group'] ) ) {
                $this->groups[] = $field['group'];
            }
        }

        if ( ! empty( $this->groups ) ) {
            $groups = array_unique( $this->groups );
            foreach( $groups as $group ) {
                $toggles[ str_replace( ' ', '_', strtolower( $group ) ) ] = $group;
            }
        }

        $this->settings_modal_toggles = array(
			'general'  => array( 'toggles' => $toggles )
		);
    }

    function map_fields() {

		if ( false === ( $fields = wp_cache_get( 'better_fields_' . get_class( $this ) ) ) ) {
            $field_file_path = BetterCore()->dir . '/includes/fields/' . str_replace( ' ', '-', strtolower( $this->name ) ) . '.php';

            if ( file_exists( $field_file_path ) ) {
                require $field_file_path;
            }

            $fields = $this->array_replace_key_assoc( $this->fields, 'title', 'label' );

            wp_cache_set( 'better_fields_' . get_class( $this ), $fields );
        }

		return $fields;

    }
    
    function get_fields() {

        // Icon source input field option
        $icon_sources = array();
        $packs = BetterSC_Icon::get_icon_sources();
        if ( is_array( $packs ) ) {
            foreach ($packs as $key => $pack) {
                $icon_sources[ $key ] = $pack['title'] . ' (' . $pack['count'] . ')';
            }
        }

        $fields = array();

        foreach ( $this->map_fields() as $key => $field ) {

            switch ( $field['type'] ) {

                case 'color':
                    $field['type'] = 'color-alpha';
                    break;

                case 'image':
                    $field['type'] = 'upload';
                    $field['upload_button_text'] = esc_attr__( 'Upload an image', 'better' );
                    $field['choose_text'] = esc_attr__( 'Choose an Image', 'better' );
                    $field['update_text'] = esc_attr__( 'Set As Image', 'better' );
                    break;

                case 'editor':
                    $field['type'] = 'tiny_mce';
                    break;

                case 'textarea':
                    $field['option_category'] = 'configuration';
                    break;

                case 'yes_no_button':
                    $field['options'] = array(
                        'on'  => esc_html__( 'Yes', 'better' ),
                        'off' => esc_html__( 'No', 'better' ),
                    );
                    $field['default'] = ( isset( $field['default'] ) && $field['default'] == true ? 'on' : 'off' );
                    break;

                case 'icon_source':
                    $field['type'] = 'select';
                    $field['options'] = $icon_sources;
                    $field['option_class'] = 'better_icon_source';
                    $field['class'] = array( 'better_iconsource' );
                    break;

                case 'icon':
                    $field['type'] = 'select';
                    $field['renderer'] = array( 'Better_Divi_Module', 'et_pb_better_get_font_icon_list' );
                    //$field['class'] = array( 'better_icontype' );
                    //$field['renderer_with_field'] = false;
                    $field['option_class'] = 'better_icon';
                    $field['class'] = array( 'better_icontype' );
                    break;

                case 'stock_image':
                    $field['label'] = '';
                    $field['type'] = 'text';
                    $field['renderer'] = array( 'Better_Divi_Module', 'et_pb_better_get_stock_image' );
                    $field['class'] = array( 'better-stockimage' );
                    break;

                case 'taxonomy':
                    $field['type'] = 'text';
                    $field['renderer'] = array( 'Better_Divi_Module', 'et_pb_better_post_taxonomy' );
                    $field['class'] = 'taxonomy none';
                    break;

                case 'terms':
                    $field['type'] = 'text';
                    $field['renderer'] = array( 'Better_Divi_Module', 'et_pb_better_post_categories' );
                    $field['class'] = 'categories none';
                    $field['option_class'] = $key;
                    // $field['renderer_options'] = array(
                    //     'use_terms' => false,
                    // );
                    //$field['taxonomy_name'] = 'category';
                    break;

                case 'cpt_template':
                    $field['type'] = 'text';
                    $field['renderer'] = array( 'Better_Divi_Module', 'et_pb_better_post_template' );
                    $field['class'] = 'template none';
                    break;

                case 'heading':
                    $field['type'] = 'hidden';
                    break;

                case 'template':
                case 'post_type':
                    $field['type'] = 'select';
                    $field['options'] = ( isset( $field['options'] ) ? $field['options'] : null );
                    break;

                case 'file':
                    $field['type'] = 'upload';
                    $field['upload_button_text'] = esc_attr__( 'Upload a file', 'better' );
                    $field['choose_text'] = esc_attr__( 'Choose a File', 'better' );
                    $field['update_text'] = esc_attr__( 'Set As File', 'better' );
                    $field['data_type'] = ( isset( $field['data_type'] ) ? $field['data_type'] : null );
                    break;

            }

            if ( ! empty( $field['renderer'] ) ) {
                $field['renderer_with_field'] = true;
            }

            if ( ! empty( $field['condition'] ) ) {
                //$pattern = '/([a-z_0-9]+)(?:\[([a-z_]+)])?(!?)$/i';
                $conditions_key = 'show_if';
                $condition = array();
                foreach ( $field['condition'] as $condition_key => $condition_value ) {

                    if ( $condition_value == 'true' ) {
                        $condition_value = 'on';
                    } elseif( $condition_value == 'false' ) {
                        $condition_value = 'off';
                    }

                    if ( strpos( $condition_key, '!' ) !== false ) {
                        //$newKey = str_replace( '!', '', $condition_key ); //preg_replace( $pattern, '', $condition_key );
                        $condition[ str_replace( '!', '', $condition_key ) ] = $condition_value;
                        $conditions_key = 'show_if_not';
                    } else {
                        $condition[ $condition_key ] = $condition_value;
                        $conditions_key = 'show_if';
                    }
                }
                $field[ $conditions_key ] = $condition;
                unset( $field['condition'] );
            }

            if ( !empty( $field['group'] ) ) {
                $field['toggle_slug'] = str_replace( ' ', '_', strtolower( $field['group'] ) );
                unset($field['group']);
            }

            $fields[ $key ] = $field;
        }

        //echo '<pre>' . print_r( $fields ) . '</pre>';

        return $fields;

    }

    static function et_pb_better_post_template( $field ) {
        $output = '<select
                name="'
                . $field['name']
                . '" 
                id="template" 
                class="et-pb-main-setting select '
                . $field['name'] . '"></select>';
    	return $output;
    }

    static function et_pb_better_post_categories( $args = array() ) {
        $defaults = array (
            'use_terms' => true,
            'term_name' => 'category',
        );
    
        $args = wp_parse_args( $args, $defaults );
    
        $term_args = array( 'hide_empty' => false, );
    
        $output = "\t" . "<% var et_pb_include_categories_temp = typeof data !== 'undefined' && typeof data.et_pb_include_categories !== 'undefined' ? data.et_pb_include_categories.split( ',' ) : []; et_pb_include_categories_temp = typeof data === 'undefined' && typeof et_pb_include_categories !== 'undefined' ? et_pb_include_categories.split( ',' ) : et_pb_include_categories_temp; %>" . "\n";
    
        if ( $args['use_terms'] ) {
            $cats_array = get_terms( $args['term_name'], $term_args );
        } else {
            $cats_array = get_categories( 'hide_empty=0' );
        }
    
        if ( empty( $cats_array ) ) {
            $taxonomy_type = $args['use_terms'] ? $args['term_name'] : 'category';
            $taxonomy = get_taxonomy( $taxonomy_type );
            $labels = get_taxonomy_labels( $taxonomy );
            $output = sprintf( '<p>%1$s</p>', esc_html( $labels->not_found ) );
        }
    
        foreach ( $cats_array as $category ) {
            $contains = sprintf(
                '<%%= _.contains( et_pb_include_categories_temp, "%1$s" ) ? checked="checked" : "" %%>',
                esc_html( $category->term_id )
            );
    
            $output .= sprintf(
                '%4$s<label><input type="checkbox" name="' . $args['name'] . '" value="%1$s"%3$s> %2$s</label><br/>',
                esc_attr( $category->term_id ),
                esc_html( $category->name ),
                $contains,
                "\n\t\t\t\t\t"
            );
        }
    
        $output = '<div class="terms" id="' . $args['name'] . '">' . $output . '</div>';
    
        return $output;
    }

    static function et_pb_better_post_taxonomy( $field ) {
        $output = '<select
                name="'
                . $field['name']
                . '" 
                id="taxonomy" 
                data-selected="" 
                class="et-pb-main-setting select '
                . $field['name'] . '">';
                
        $taxonomy_names = get_object_taxonomies( 'post' );

        if ( is_array( $taxonomy_names ) ) {
            foreach ( $taxonomy_names as $taxonomy_name ) {
                $selected = '';
                $option_value_string = (string) $taxonomy_name;
                $value_string = 'category';
                if ( $option_value_string === $value_string ) {
                    $selected = ' selected="selected"';
                }
                $tax_obj = get_taxonomy( $taxonomy_name );
                $output .= '<option class="' . $taxonomy_name . '" value="' . $taxonomy_name . '" ' . $selected . '>' . $tax_obj->label . '</option>';
                
            }
        }

        $output .= '</select>';
        $output .= '<% jQuery( document ).trigger( \'posts-loaded\', [ \'posts\' ] ); %>';
    	return $output;
    }

    static function et_pb_better_get_font_icon_list( $field ) {
		//$output = '<div class="better_icon_select_container et_better_font_icon ' . $field['source'] . '" id="'  . uniqid( 'better_icon_' ) . '"></div>';
		$output = '<% jQuery( \'body\' ).trigger( \'icon-param-loaded\', [ \'icon\' ] ); %>';
    	return $output;
    }

    static function et_pb_better_get_stock_image() {
		
		$output = '<div class="et-pb-option et-pb-option--text" tabindex="-1" data-option_name="search-image">
			<label for="stock-image-searcher">Stock Image Search: </label>
			<div class="et-pb-option-container betterbuilder-stock-image-search">
				<div id="better-stockimage-preview"></div>
				<input id="stock-image-searcher" class="regular-text et-pb-main-setting" type="text" placeholder="Search free high-resolution photos...">
			</div> <!-- .et-pb-option-container -->
		 </div><div id="better-stockimage-wrap"></div>';

		$output .= '<% jQuery( \'body\' ).trigger( \'stockimage-param-loaded\', [ \'image\' ] ); %>';

		return $output;
	}

    function render( $atts, $content = null, $render_slug ) {
		$shortcode = 'better_' . str_replace( ' ', '_', strtolower( $this->name ) );

		$att_map = '';

		foreach ( $this->map_fields() as $key => $field ) {
            $value = $this->props[ $key ];
            
            switch ( $field['type'] ) {
				case 'yes_no_button':
					$value = $value === 'on' ? 'true' : 'false';
					break;
            }
            
			$att_map .= "$key=\"$value\" ";
		}

        if ( ! empty( $this->props['content'] ) ) {
			return do_shortcode('[' . $shortcode . ' ' . $att_map . ' ' . $this->module_id() . ' class="' . $this->module_classname( $render_slug ) . '"]' . $this->props['content'] . '[/' . $shortcode . ']');
		} else {
			return do_shortcode('[' . $shortcode . ' ' . $att_map . ' ' . $this->module_id() . ' class="' . $this->module_classname( $render_slug ) . '"]');
		}
    }
    
	private function array_replace_key_assoc($array, $search, $replace) {

        $newArray = array();
        
        foreach ( $array as $key => $value ) {
            if ( array_key_exists( $search, $value ) ) {
                $value[ $replace ] = $value[ $search ];
                unset($value[ $search ]);
            }
            $newArray[ $key ] = $value;
        }

        return $newArray;
        
    }
      
}