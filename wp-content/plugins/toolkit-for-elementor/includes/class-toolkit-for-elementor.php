<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://toolkitforelementor.com
 * @since      1.0.0
 *
 * @package    Toolkit_For_Elementor
 * @subpackage Toolkit_For_Elementor/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Toolkit_For_Elementor
 * @subpackage Toolkit_For_Elementor/includes
 * @author     ToolKit For Elementor <support@toolkitforelementor.com>
 */
class Toolkit_For_Elementor {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Toolkit_For_Elementor_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TOOLKIT_FOR_ELEMENTOR_VERSION' ) ) {
			$this->version = TOOLKIT_FOR_ELEMENTOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'toolkit-for-elementor';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Toolkit_For_Elementor_Loader. Orchestrates the hooks of the plugin.
	 * - Toolkit_For_Elementor_i18n. Defines internationalization functionality.
	 * - Toolkit_For_Elementor_Admin. Defines all hooks for the admin area.
	 * - Toolkit_For_Elementor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'includes/class-toolkit-for-elementor-loader.php';
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'includes/functions-toolkit-for-elementor.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'includes/class-toolkit-for-elementor-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'admin/class-toolkit-for-elementor-admin.php';
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'admin/class-theme-disable-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'public/class-toolkit-for-elementor-public.php';
		require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'public/class-theme-disable-public.php';

		/**
		 * Elementor ELEMENT CALL LOAD
		 * GT METRIX CLASS LOAD
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/elementor-class.php';
		require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/settings.php';
		
		/**
		 * Syncer
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/syncer-auth.php';
		require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/syncer.php';
		
		/**
		 * LAZY LOAD
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/lazy-load.php';
		/**
		 * MINIFY
		 */
		require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/minify.php';
		$this->loader = new Toolkit_For_Elementor_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Toolkit_For_Elementor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Toolkit_For_Elementor_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Toolkit_For_Elementor_Admin( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		//$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'edit_user_profile', $plugin_admin , 'extra_user_profile_fields' );
        $this->loader->add_action( 'edit_user_profile_update', $plugin_admin , 'save_extra_user_profile_fields' );
        $this->loader->add_action( 'init', $plugin_admin , 'register_post_types' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin , 'register_meta_boxes' );
        $this->loader->add_action( 'save_post_toolkit_template', $plugin_admin , 'save_toolkit_template_details' );
        //enable ninja stack options htaccess
        $this->loader->add_action( 'mod_rewrite_rules', $plugin_admin , 'ninja_stack_settings' );
        //remove cache file on plugin deactivation or theme changed
        $this->loader->add_action( 'activated_plugin', $plugin_admin , 'detect_plugin_theme_change' );
        $this->loader->add_action( 'deactivated_plugin', $plugin_admin , 'detect_plugin_theme_change' );
        $this->loader->add_action( 'after_switch_theme', $plugin_admin , 'detect_plugin_theme_change' );
        $this->loader->add_action( 'admin_bar_menu', $plugin_admin , 'admin_bar_link', 110 );
        $this->loader->add_action( 'init', $plugin_admin , 'clear_plugin_cache' );

        $theme_disable = new Theme_Disable_Admin();
        //check updates for plugin
        $this->loader->add_action( 'admin_init', $theme_disable, 'edd_plugin_updater' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Toolkit_For_Elementor_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_body_open', $plugin_public, 'bodytag_custom_code' );
		$this->loader->add_action( 'init', $plugin_public, 'minify_css_js_fonts', 9 );

        $theme_disable = new Theme_Disable_Public();

        $unloadOpts = get_option('toolkit_unload_options', array());
        if( $unloadOpts && is_array($unloadOpts) && isset($unloadOpts['disable_emojis']) && $unloadOpts['disable_emojis'] == 'on' ){
            $this->loader->add_action( 'init', $plugin_public, 'disable_emojis');
        }

        if( $unloadOpts && is_array($unloadOpts) && isset($unloadOpts['disable_gutenberg']) && $unloadOpts['disable_gutenberg'] == 'on' ){
            $this->loader->add_action( 'wp_print_styles', $plugin_public, 'disable_gutenberg_css', 100);
        }

        if( $unloadOpts && is_array($unloadOpts) && isset($unloadOpts['disable_jqmigrate']) && $unloadOpts['disable_jqmigrate'] == 'on' ){
            $this->loader->add_action( 'wp_print_scripts', $plugin_public, 'disable_jquery_migrate', 100);
        }

        if( $unloadOpts && is_array($unloadOpts) && isset($unloadOpts['disable_commentreply']) && $unloadOpts['disable_commentreply'] == 'on' ){
            $this->loader->add_filter( 'wp_print_scripts', $plugin_public, 'disable_comment_reply', 100);
        }

        if( $unloadOpts && is_array($unloadOpts) && isset($unloadOpts['disable_woohomeajax']) && $unloadOpts['disable_woohomeajax'] == 'on' ){
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dequeue_woocommerce_cart_fragments', 11);
        }

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Toolkit_For_Elementor_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
