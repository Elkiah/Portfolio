<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Better_Shortcodes {

	/**
	 * The single instance of Better_Shortcodes.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public $list = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'better_';

		add_action( 'plugins_loaded', array( $this, 'load_shortcodes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'init', array( $this, 'register_meta' ) );
	}

	/**
	 * Register meta.
	 */
	public function register_meta() {
		register_meta(
			'post', '_betterbuilder_attr', array(
				'show_in_rest'  => true,
				'single'        => true,
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_meta(
			'post', '_betterbuilder_dimensions', array(
				'show_in_rest'  => true,
				'single'        => true,
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	public function enqueue_scripts() {

		foreach ( $this->list as $key => $shortcode ) {

			$shortcode->register_assets();

		}

	}

	/**
	 * Autoload shortcodes
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_shortcodes() {

		// Load Shortcodes
		$paths = $this->parent->paths( 'includes/shortcodes/' );

		foreach ($paths as $key => $path) {

			foreach ( glob( "$path*.php" ) as $filename ) {

				if ( ! file_exists( $filename ) ) {
					continue;
				}

				$words = explode( '-', str_replace( '.php', '', basename( $filename ) ) );

				if ( strpos( $words[0], '_' ) === 0 ) {
					continue;
				}

				require_once $filename;

			}
		}

		$shortcode_builder = new Better_Shortcode();

		$this->list = array();

		foreach ( get_declared_classes() as $class ){

		    if ( is_subclass_of( $class, "Better_Shortcode" ) ) {

		    	$shortcode = new $class;
		    	$this->list[ $class ] = $shortcode;

		    	$shortcode->map( $shortcode_builder );
		    }

		}

	}

	/**
	 * Main Better_Shortcodes Instance
	 *
	 * Ensures only one instance of Better_Shortcodes is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see BetterCore()
	 * @return Main Better_Shortcodes instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
