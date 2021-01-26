<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterB_Elementor extends Better_Builder {
    // See the following for an example:
    // github.com/dtbaker/elementor-custom-element/blob/master/elementor-custom-element.php

	public function __construct() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

        add_action( 'elementor/init', function() {
            \Elementor\Plugin::$instance->elements_manager->add_category(
            	'betterbuilder-elements', // the name of the category
            	[
            		'title' => esc_html__( 'Better Builder', 'better' ),
            		'icon' => 'fa fa-header', //default icon
            	],
            	1 // position
            );
		} );

		// Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
		add_action( 'elementor/controls/controls_registered', array( $this, 'init_controls' ) );

		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'admin_enqueue_styles' ) );
		add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'admin_enqueue_styles' ) );

		add_action( 'elementor/preview/enqueue_styles', array( $this, 'widget_styles' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'widget_scripts' ) );
		
        add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'widget_styles' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'widget_scripts' ) );
		
	   // add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'widget_scripts' ) );
	   
		unset(BetterCore()->shortcodes->list['BetterSC_Box']);

	}

	public function widget_scripts() {

		$better_scripts = array(
			'jquery.animateSprite',
			'amplitude',
			'foundation',
			'freezeframe',
			'better.icon',
			'interactive_3d',
			'jquery.event.move',
			'twentytwenty',
			'jquery.event.move',
			'medium-zoom',
			'bodymovin',
			'panorama-viewer',
			'fittext',
			//'vanilla.tilt',
			//'better.tilt',
			'typed',
			'better.stock-image',
			'better.posts',
		);

		foreach ( $better_scripts as $script_handle ) {
			wp_enqueue_script( $script_handle );
		}

	}

	public function widget_styles() {

		$better_styles = array(
			'amplitude-blue-playlist',
			'amplitude-flat-black',
			'amplitude-multiple-songs',
			'amplitude-single-song',
			'foundation',
			'freezeframe',
			'interactive_3d',
			'twentytwenty',
			'better.overlay',
			'better.tilt',
			'panorama-viewer',
			'better.stock-image',
			'better.video-popup'
		);

		foreach ( $better_styles as $style_handle ) {
			wp_enqueue_style( $style_handle );
		}

	}

    public function admin_enqueue_styles() {
		wp_enqueue_style( 'better.elementor', BetterCore()->assets_url . 'css/elementor/elementor.min.css', null, BetterCore()->_version );
		wp_enqueue_style( 'better.readmore' );
	}
	
	public function includes() {
		require_once ( __DIR__ . '/elementor/base.php' );

		// eval( 'namespace Elementor; class Widget_Better_Video extends Better_Widget_Base {
		// };' );
	}

   	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
    public function init_widgets() {
	    // include base file
	    $this->includes();

		foreach ( BetterCore()->shortcodes->list as $key => $shortcode ) {

			$shortcode_name = str_replace( 'BetterSC_', 'Widget_Better_', $key );

			if ( ! class_exists( $shortcode_name ) ) {
                eval( 'namespace Elementor; class ' . $shortcode_name . ' extends Better_Widget_Base {
				};' );
            }

			$widget_class = "\Elementor" . "\\$shortcode_name";

			if ( class_exists( $widget_class ) ) {
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $widget_class );
			}
        }
	}

	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_controls() {
		$better_controls = array_map( 'basename', glob( dirname( __FILE__ ) . '/elementor/controls/*.php' ) );

		foreach( $better_controls as $key => $value ) {
   			require __DIR__ . '/elementor/controls/' . $value;
		}

		// Register control
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'better_icon', new \Elementor\Control_Better_Icon() );
		$controls_manager->register_control( 'better_iconsource', new \Elementor\Control_Better_IconSource() );
		$controls_manager->register_control( 'better_stockimage', new \Elementor\Control_Better_StockImage() );
		$controls_manager->register_control( 'better_select', new \Elementor\Control_Better_Select() );
	}

}
