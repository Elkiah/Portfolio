<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Better_Widget_Base extends Widget_Base {
	protected $fields = array();
    public $name;
    public $title;
    public $icon;

    /**
	 * Get widget name.
	 *
	 * Retrieve spacer widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve spacer widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve spacer widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'betterbuilder-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the spacer widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'betterbuilder-elements' ];
	}

    public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->title = str_replace( '_', ' ', str_replace( 'Elementor\Widget_Better_', '', get_class( $this ) ) );
		$this->name = 'better-' . str_replace( ' ', '-', strtolower( $this->title ) );
	}
	
	public function get_fields() {
		if ( empty( $this->fields ) ) {
			if ( false === ( $fields = wp_cache_get( 'better_fields_' . get_class( $this ) ) ) ) {
				$field_file_path = BetterCore()->dir . '/includes/fields/' . str_replace( 'elementor\widget_Better', '', str_replace( ' ', '-', strtolower( $this->title ) ) ) . '.php';

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

    /**
	 * Register spacer widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		
        $fields = $this->get_fields();

        foreach ( $fields as $key => $field ) {

            $param = [
                'label' => ! empty( $field['title'] ) ? $field['title'] : '',
                'type' => Controls_Manager::TEXT,
                'classes' => ! empty( $field['class'] ) ? $field['class'] : '',
                'placeholder' => ! empty( $field['placeholder'] ) ? $field['placeholder'] : '',
                'default' => ! empty( $field['default'] ) ? $field['default'] : '',
				'description' => ! empty( $field['description'] ) ? $field['description'] : '',
				'dynamic' => ! empty( $field['dynamic'] ) ? $field['dynamic'] : '',
				'selectors' => ! empty( $field['selectors'] ) ? $field['selectors'] : '',
            ];

            switch ( $field['type'] ) {
                case 'range':
					$param['type'] = Controls_Manager::SLIDER;
					
					$param['range'] = array (
						'px' => ( isset( $field['range_settings'] ) ? $field['range_settings'] : null )
					);
					
					$param['default'] = array(
						'unit' => 'px', 
					);

					if ( isset( $field['default'] ) ) {
						$param['default']['size'] = $field['default'];
					} else {
						$param['default']['size'] = '';
					}

                    break;

                case 'select':
                case 'post_type':
                case 'template':
                    $param['type'] = Controls_Manager::SELECT;
                    $param['options'] = ( isset( $field['options'] ) ? $field['options'] : null );
					break;

                case 'choose':
                    $param['type'] = Controls_Manager::CHOOSE;
                    $param['options'] = ( isset( $field['options'] ) ? $field['options'] : null );
					break;
					
				case 'select2':
				case 'terms':
					$param['type'] = Controls_Manager::SELECT2;
					$param['options'] = ( isset( $field['options'] ) ? $field['options'] : null );
					$param['multiple'] = true;
                    break;

                case 'yes_no_button':
                    $param['type'] = Controls_Manager::SWITCHER;
                    $param['label_on'] = ( isset( $field['options'] ) ? $field['options']['true'] : esc_html__( 'Yes', 'better' ) );
                    $param['label_off'] = ( isset( $field['options'] ) ? $field['options']['false'] : esc_html__( 'No', 'better' ) );
                    $param['return_value'] = 'true';
                    $param['default'] = ! empty( $field['default'] ) ? ( $field['default'] ? 'true' : 'false' ) : 'false';
					break;

				case 'heading':
					$param['type'] = Controls_Manager::HEADING;
					$param['separator'] = ( isset( $field['separator'] ) ? $field['separator'] : null );
					break;

				case 'color':
					$param['type'] = Controls_Manager::COLOR;
					$param['scheme'] = ( isset( $field['scheme'] ) ? $field['scheme'] : [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					] );
					break;

				case 'image':
					$param['type'] = Controls_Manager::MEDIA;
					$param['default'] = array (
						'url' => ( isset( $field['default'] ) ? $field['default'] : Utils::get_placeholder_image_src() )
					);
					break;

				case 'textarea':
					$param['type'] = Controls_Manager::TEXTAREA;
					break;

				case 'editor':
					$param['type'] = Controls_Manager::WYSIWYG;
					break;

				case 'url':
					$param['type'] = Controls_Manager::URL;
					break;

				case 'spacing':
					$param['type'] = Controls_Manager::DIMENSIONS;
					$param['size_units'] = ( isset( $field['units'] ) ? $field['units'] : null );
					$param['allowed_dimensions'] = ( isset( $field['allowed_dimensions'] ) ? $field['allowed_dimensions'] : array( 'top', 'right', 'bottom', 'left' ) );
					$param['default'] = array (
						'isLinked' => true,
					);
					break;

				case 'font_family':
					$param['type'] = Controls_Manager::FONT;
					break;

				case 'stock_image':
					$param['type'] = 'better_stockimage';
					break;
					
				case 'better_select':
				case 'cpt_template':
				case 'taxonomy':
					$param['type'] = 'better_select';
					$param['options'] = ( isset( $field['options'] ) ? $field['options'] : null );
					break;

				case 'icon_source':
					$param['type'] = 'better_iconsource';
					break;

				case 'icon':
					$param['type'] = 'better_icon';
					break;

                default:
                    # code...
                    break;
			}
			
			if ( ! empty( $field['condition'] ) ) {
				$param['condition'] = $field['condition'];
			}

            if ( isset( $field['group'] ) && ! empty( $field['group'] ) ) {
                $this->start_controls_section(
                    'section_better_' . str_replace( ' ', '_', strtolower( $field['group'] ) ),
                    [
                        'label' => ! empty( $field['group'] ) ? $field['group'] : '',
                    ]
                );
            } else {
				$this->start_controls_section(
                    'section_better_' . str_replace( ' ', '_', strtolower( $this->title ) ),
                    [
                        'label' => $this->title,
                    ]
                );
			}

            $this->add_control( $key, $param );

            $this->end_controls_section();
        }

	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {

		if ( isset( $this->name ) ) {
			$content_template = BetterCore()->dir . '/templates/elementor/' . str_replace( 'better-', '', strtolower( $this->name ) ) . '.php';

			if ( file_exists( $content_template ) ) {
				require $content_template;
			}
		}
		
	}
	
	/**
	 * Render spacer widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$shortcode = 'better_' . str_replace( ' ', '_', strtolower( $this->title ) );

		$att_map = '';

		$fields = $this->get_fields();

		foreach ( $fields as $key => $field ) {
			$value = $settings[ $key ];

			switch ( $field['type'] ) {
				case 'range':
					$value = $value['size'];
					break;

				case 'image':
				case 'url':
					$value = $value['url'];
					break;

				case 'spacing':
					$spacing = array();
					$pos = array( 'top', 'right', 'bottom', 'left' );
					for ( $i=0; $i < count($pos); $i++ ) { 
						if ( ! empty( $value[$pos[$i]] ) ) {
							$spacing[] = $value[$pos[$i]] . $value['unit'];
						} else {
							$spacing[] = 0;
						}
					}
					$value = implode( ' ', $spacing );
					break;

				case 'select2':
				case 'terms':
					$value = implode( ',', (array) $value );
					break;
			}
			
			$att_map .= "$key=\"$value\" ";
			
		}

		if ( ! empty( $settings['content'] ) ) {
			echo do_shortcode('[' . $shortcode . ' ' . $att_map . ' id="' . trim( $settings['_element_id'] ) . '" class="' . $settings['_element_classes'] . '"]' . $settings['content'] . '[/' . $shortcode . ']');
		} else {
			echo do_shortcode('[' . $shortcode . ' ' . $att_map . ' id="' . trim( $settings['_element_id'] ) . '" class="' . $settings['_element_classes'] . '"]');
		}

		BetterCore()->inline_svg();
        
	}

}