<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Better_Builders {

	/**
	 * The single instance of Better_Builders.
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
		
		add_action( 'plugins_loaded', array( $this, 'load_builders' ) );
	}

	/**
	 * Autoload builders
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_builders() {

		$paths = $this->parent->paths( 'includes/builders/' );

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

		$this->list = array();

		foreach ( get_declared_classes() as $class ){

		    if ( is_subclass_of( $class, "Better_Builder" ) ) {
		    	
		    	$builder = new $class();

		    	$this->list[ $class ] = $builder;
		    	
		    }

		}		

	}

	/**
	 * Main Better_Builders Instance
	 *
	 * Ensures only one instance of Better_Builders is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see BetterCore()
	 * @return Main Better_Builders instance
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