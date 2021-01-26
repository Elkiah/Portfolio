<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Better_Custom_Post_Types {

	/**
	 * The single instance of Better_Custom_Post_Types.
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

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'better_';
		
		add_action( 'typerocket_loaded', array( $this, 'register_custom_post_types' ) );		
	}

	/**
	 * Autoload custom post types
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public function register_custom_post_types() {

		$paths = $this->parent->paths( 'includes/custom-post-types/' );

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

	}

	public function get_posts_list( $post_type ) {
		$_posts = [];
		$posts = get_posts( array( 'post_type' => $post_type ) );
		foreach( $posts as $post ) {
			$_posts[ $post->ID ] = $post->post_title;
		}
		return $_posts;
	}

	public function get_terms_list( $taxonomy, $item_id = null ) {
		global $post;

	    if (isset($post->ID) && is_null($item_id)) {
	        $item_id = $post->ID;
		}

		$terms = get_the_terms( $item_id, $taxonomy );
		$termList = array();

		if( is_array( $terms ) && count( $terms ) > 0 ) {
			foreach ( $terms as $key => $term ) {
				$termList[] = $term->name;	
			}
		}

		return $termList;
	}

	public function get_term_posts( $post_type, $taxonomy, $term ) {
		$_posts = array();

		$args = array(
		    'post_type' => $post_type,
		    'no_paging' => true,
		    'tax_query' => array(
		        array(
		            'taxonomy' => $taxonomy,
		            'field'    => 'slug',
		            'terms'    => $term,
		        ),
		    ),
		);
		$posts = get_posts( $args );
		foreach( $posts as $post ) {
			$_posts[] = $post->ID;
		}
		wp_reset_postdata();

		return $_posts;
	}

	/**
	 * Main Better_Custom_Post_Types Instance
	 *
	 * Ensures only one instance of Better_Custom_Post_Types is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see BetterCore()
	 * @return Main Better_Custom_Post_Types instance
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