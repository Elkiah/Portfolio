<?php

if ( ! defined( 'ABSPATH' ) ) exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BetterCore {
	protected $templates_folder = 'templates';
	protected $theme_template_folder = 'betterbuilder/templates';

	/**
	 * The single instance of BetterCore.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * Shortcodes class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $shortcodes = null;

	/**
	 * Builders class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $builders = null;

	/**
	 * Custom Post Types class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $custom_post_types = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The path to the main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $path;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Dynamically generated CSS
	 * @var string
	 * @access public
	 * @since 1.0.0
	 */
	public $dynamic_css;

	public $dynamic_svg;

	public $dynamic_js;

	/**
	 * List of installed plugins
	 * @var string
	 * @access public
	 * @since 1.0.0
	 */
	public $plugins;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'better';

		// Load plugin environment variables
		$this->file = $file;
		$this->path = plugin_dir_path( $file );
		$this->dir = dirname( $this->file );
		$this->url = esc_url( trailingslashit( plugins_url( '/', $this->file ) ) );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$this->plugins = array( );
		$this->register_plugin( $this->file );

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// ajax
		add_action( 'wp_ajax_better_icon_type_action', array( $this, 'better_icon_type_action' ) );

		add_action( 'wp_ajax_post_infinite_load', array( $this, 'posts_infinite_load' ) );
		add_action( 'wp_ajax_nopriv_post_infinite_load', array( $this, 'posts_infinite_load' ) );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load dynamic JS & CSS
		add_action( 'wp_footer', array( $this, 'wp_footer' ), 10, 1 );

		// Handle localisation
		$this->load_plugin_textdomain();

		add_action( 'init', array( $this, 'load_localisation' ), 0 );

		add_action( 'typerocket_loaded', array( $this, 'typerocket_loaded' ) );

		add_filter( 'upload_mimes', array( $this, 'upload_mimes' ), 1, 1 );

		add_image_size( 'better-post-grid-480x480', 480, 480, true );
		add_image_size( 'better-post-grid-370x250', 370, 250, true );
		add_image_size( 'better-post-grid-370x560', 370, 560, true );
	} // End __construct ()

	function register_plugin( $file ) {

		$plugin_data = get_plugin_data( $file );
		$token = strtolower( str_replace( ' ', '-', $plugin_data['Name']) );

		$this->plugins[ $token ] = array(
			'name' => $plugin_data['Name'],
			'file' => $file,
			'path' => plugin_dir_path( $file ),
			'dir' => dirname( $file ),
			'url' => esc_url( trailingslashit( plugins_url( '/', $file ) ) ),
			'assets_dir' => trailingslashit( dirname( $file ) ) . 'assets',
			'assets_url' => esc_url( trailingslashit( plugins_url( '/assets/', $file ) ) ),
			'version' => $plugin_data['Version'],
			'token' => $token
		);

	}

	public function posts_infinite_load() {
		global $post;
		
		$args = array(
			'post_type' => $_POST['post_type'],
			'orderby' => $_POST['orderby'],
			'order' => $_POST['order'],
			'paged'=> $_POST['paged'],
			'posts_per_page' => $_POST['posts_per_page'],
			'post_status' => 'publish',
		);

		$atts = $_POST['atts'];

		if ( $_POST['categories'] != '' && isset( $_POST['taxonomy'] ) && $_POST['taxonomy'] != '' ) {
			$iscat_array = true;
			$field = '';
		
			if ( strpos( $_POST['categories'], ',' ) ) {
				$cat_array = explode( ',', str_replace( ' ', '', $_POST['categories'] ) );
		
				$field = array_filter( $cat_array, 'is_numeric' ) ? 'term_id' : 'slug';
		
				$args['tax_query'] = array(
					array(
						'taxonomy' => $_POST['taxonomy'],
						'field'  => $field,
						'terms'  => $cat_array
					)
				);
			} else {
				$cat_array = array( $_POST['categories'] );
		
				$field = array_filter( $cat_array, 'is_numeric' ) ? 'term_id' : 'slug';
		
				$args['tax_query'] = array(
					array(
						'taxonomy' => $_POST['taxonomy'],
						'field'  => $field,
						'terms'  => array( $_POST['categories'] )
					)
				);
			}
		}
		
		// if ( ! empty( $_POST['taxonomies'] ) ) {
		// 	//$args = $this->get_tax_query_of_taxonomies( $args, $_POST['taxonomies'] );
		// }

		if ( isset( $_POST['extra_taxonomy'] ) && ! empty( $_POST['extra_taxonomy'] ) ) {
			$args = $this->get_tax_query_of_taxonomies( $args, $_POST['extra_taxonomy'] );
		}

		$post_query = new WP_Query( $args );

		$response = array(
			'max_num_pages' => $post_query->max_num_pages,
			'found_posts'   => $post_query->found_posts,
			'count'         => $post_query->post_count,
		);

		ob_start();
		
		if ( $post_query->have_posts() ) {
			while ( $post_query->have_posts() ) {
				$post_query->the_post();

				if ( has_post_thumbnail() ) {
					$post_thumb_class = 'has-thumb';
				} else {
					$post_thumb_class = 'no-thumb';
				}
				
				$post_classes = array(
					'bb-post-item',
					$post_thumb_class
				);

				if ( isset( $atts['shadow'] ) && ! empty( $atts['shadow'] ) ) {
					$post_classes[] = 'bb-shadow';
				}

				if ( $_POST['cpt_template'] !== 'grid-with-caption' ) {
					$post_classes[] = 'bb-overlay';
				}
		
				if ( $_POST['cpt_template'] === 'carousel-slider' ) {
					$post_classes[] = 'swiper-slide';
				}

				$terms = get_the_terms( $post->ID, $_POST['taxonomies'] );

				if ( $terms && ! is_wp_error( $terms ) ) {
					$categories_list = array();

					foreach ( $terms as $term ) {
						$post_classes[] = $term->slug;
					}
				}

				$post_classes = get_post_class( implode( ' ', $post_classes ) );
				$atts['post_classes'] = implode( ' ', $post_classes );

				BetterCore()->template( 'custom-post/' . $_POST['post_type'] . '/' . $_POST['cpt_template'], null, $atts, true );
			}
		}

		$template = ob_get_contents();
		ob_clean();

		$response['template'] = $template;

		echo json_encode( $response );

		wp_die();
	}

	public function get_tax_query_of_taxonomies( $post_args, $taxonomies ) {
		if ( empty( $taxonomies ) ) {
			return $post_args;
		}

		$terms       = explode( ', ', $taxonomies );
		$tax_queries = array(); // List of taxonomies.

		if ( ! isset( $post_args['tax_query'] ) ) {
			$post_args['tax_query'] = array();

			foreach ( $terms as $term ) {
				$tmp       = explode( ':', $term );
				$taxonomy  = $tmp[0];
				$term_slug = $tmp[1];
				if ( ! isset( $tax_queries[ $taxonomy ] ) ) {
					$tax_queries[ $taxonomy ] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => array( $term_slug ),
					);
				} else {
					$tax_queries[ $taxonomy ]['terms'][] = $term_slug;
				}
			}
			$post_args['tax_query']             = array_values( $tax_queries );
			$post_args['tax_query']['relation'] = 'OR';
		} else {
			foreach ( $terms as $term ) {
				$tmp       = explode( ':', $term );
				$taxonomy  = $tmp[0];
				$term_slug = $tmp[1];

				foreach ( $post_args['tax_query'] as $key => $query ) {
					if ( is_array( $query ) ) {
						if ( $query['taxonomy'] == $taxonomy ) {
							$post_args['tax_query'][ $key ]['terms'][] = $term_slug;
						}
					}
				}
			}
		}

		return $post_args;
	}

	function better_icon_type_action() {
		// check for rights
		if ( !current_user_can( 'edit_pages' ) && !current_user_can( 'edit_posts' ) )
			die( __( "You are not allowed to be here" ) );
	
		if ( !empty( $_POST["source"] ) ) {
	
			$raw_icons = BetterSC_Icon::get_source_icons( $_POST["source"] );
			$results = array();
			$better_icon = new BetterSC_Icon;
			$svg = '';

			$results['icon_source'] = $_POST["source"];
	
			foreach ($raw_icons as $key => $value) {
				$results['icons'][] = array(
					'text' => $value,
					'value' => $value,
					'svg' => $better_icon->get_svg( $_POST["source"], $value, 'svg' )
				);
			}
	
			if( isset( $_POST["type_id"] ) ) {
				$results['type_id'] = $_POST["type_id"];
			}
	
			echo json_encode( $results );
		}
	
		die();
	}

	function upload_mimes( $mime_types ) {
		$mime_types['svg'] = 'image/svg+xml';     // Adding .svg extension
		$mime_types['json'] = 'application/json'; // Adding .json extension
		return $mime_types;
	}

	function typerocket_loaded() {
		// register post types, taxonomies, and pages
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		foreach ( $this->plugins as $key => $plugin ) {
			wp_register_style( $plugin['token'] . '-frontend', esc_url( $plugin['assets_url'] ) . 'css/frontend.css', array(), $plugin['version'] );
			wp_enqueue_style( $plugin['token'] . '-frontend' );
		}
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {

		// wp_register_script( 'js.cookie.js', esc_url( $this->assets_url ) . 'js/js.cookie.js', array( 'jquery' ), '2.1.4' );
		// wp_enqueue_script( 'js.cookie.js' );

	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		// wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		// wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		// wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		// wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load footer content
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wp_footer ( $hook = '' ) {
		$this->do_inline_css( $this->dynamic_css, true );

		$this->inline_svg();
	} // End admin_enqueue_scripts ()

	public function get_wordpress_image_sizes() {

		global $_wp_additional_image_sizes;

        $sizes = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach ( $get_intermediate_image_sizes as $_size ) {

            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size ] = array(
                    'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                    'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                    'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
                );
            }

        }

        return $sizes;

	}

	public function get_image_src( $image, $size = 'full' ) {

		global $wpdb;

        if ( !is_numeric( $image ) ) {
        	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image ));

        	if( !empty( $attachment[0] ) ) {
        		$image = $attachment[0];
        	}
        }

        $image_src = is_numeric( $image ) ? wp_get_attachment_image_src( $image, $size ) : array( $image, null, null );

		return $image_src;

	}

	public function get_file_src( $file ) {
		if ( ! is_numeric( $file ) ) {
			return $file;
		}

		return wp_get_attachment_url( $file );
	}

	public function do_inline_css( $css, $echo = false ) {

		if ( empty( $css ) ) return '';

		$output = "<style>";

		if ( is_array( $css ) ) {

			$output .= "/* dynamic styles */\n";

			foreach ( $css as $key => $value ) {
				$output .= $value . "\n";
			}

		} else {

			$output .= $css;

		}

		$output .= "</style>";

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}

	public function do_style( $styles, $wrap = true, $echo = true ) {

		$output = '';

		foreach ( $styles as $key => $value ) {
			if ( isset( $value ) && $value != null ) {
				$output .= "$key: $value; ";
			}
		}

		if ( $wrap ) {
			$output = empty( $output ) ? '' : 'style="' . $output . '"';
		}

		if ( $echo ) {

			echo $output;

		}

		return $output;
	}

	public function do_attributes( $attributes, $echo = true ) {

		$output = '';

		foreach ( $attributes as $key => $value ) {
			if ( isset( $value ) && $value != null ) {
				$output .= "$key=\"$value\" ";
			}
		}

		$output = trim( $output );

		if ( $echo ) {

			echo $output;

		}

		return $output;
	}

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'better', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'better';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Returns the path of a resource
	 * @param  string $to [description]
	 * @return [type]     [description]
	 */
	public function path( $to = '' ) {
		return apply_filters( $this->_token . '_path', $this->path ) . $to;
	}

	public function paths( $to = '' ) {
		$paths = array();

		foreach ( $this->plugins as $key => $plugin ) {
			$paths[$key] = apply_filters( $plugin['token'] . '_path', $plugin['path'] ) . $to;
		}

		return $paths;
	}

	public function get_template_part( $slug, $name = null, $load = true ) {

		do_action( 'get_template_part_' . $slug, $slug, $name );

		$templates = array();

		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '.php';
		}

		$templates[] = $slug . '.php';

		$templates = apply_filters( $this->_token . '_get_template_part', $templates, $slug, $name );

		return $this->locate_template( $templates, $load, false );

	}

	public function locate_template( $template_names, $load = false, $require_once = true ) {

		$filename = false;

		$theme_template_folder = trailingslashit( ( '' !== $this->theme_template_folder )
			? $this->theme_template_folder
			: $this->_token
		);

		foreach ( (array) $template_names as $template_name ) {

			if ( empty( $template_name ) ) {
				continue;
			}

			$template_name = untrailingslashit( $template_name );

			// Check child theme first
			$child = get_stylesheet_directory() . '/' . $theme_template_folder . $template_name;

			if ( file_exists( $child ) ) {
				$filename = $child;
				break;
			}

			$parent = get_template_directory() . '/' . $theme_template_folder . $template_name;

			if ( file_exists( $parent ) ) {
				$filename = $parent;
				break;
			}

			$plugin = $this->path( "$this->templates_folder/$template_name" );

			if ( file_exists( $plugin ) ) {
				$filename = $plugin;
				break;
			}
		}

		if ( $load && ! empty( $filename ) ) {
			load_template( $filename, $require_once );
		}

		return $filename;

	}

	public function template( $slug, $name = null, $args = null, $echo = true ) {

		ob_start();

		$template = $this->get_template_part( $slug, $name, false );

		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		if ( $template ) {
			include $template;
		}

		$contents = ob_get_clean();

		if ( $echo ) {
			echo $contents;
		}

		return $contents;

	}

	public function add_dynamic_css( $key, $css ) {

		if ( empty( $this->dynamic_css ) ) $this->dynamic_css = array();

		$this->dynamic_css[ $key ] = $css;

	}

	public function add_dynamic_js( $key, $js ) {

		if ( empty( $this->dynamic_js ) ) $this->dynamic_js = array();

		$this->dynamic_js[ $key ] = $js;

	}

	public function add_dynamic_svg( $key, $svg ) {

		if ( empty( $this->dynamic_svg ) ) $this->dynamic_svg = array();

		$this->dynamic_svg[ $key ] = $svg;

	}

	public function inline_svg() {
		if ( isset( $this->dynamic_svg ) ) {
			echo '<svg id="better-svg-definition" xmlns="http://www.w3.org/2000/svg" style="display: none;">';
			echo "
			        <!-- shortcode svg -->
			";

			foreach ( $this->dynamic_svg as $key => $value ) {
				echo $value . "\n";
			}

			echo "</svg>";
		}
	}

	/**
	 * Main BetterCore Instance
	 *
	 * Ensures only one instance of BetterCore is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see BetterCore()
	 * @return Main BetterCore instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
