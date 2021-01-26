<?php
/*
 * Plugin Name: Better Builder
 * Version: 1.0.3
 * Plugin URI: https://wpbetterbuilder.com/
 * Description: An add-on for WordPress page builders. Works with Gutenberg, WPBakery Page Builder (formerly Visual Composer), Divi, Elementor, and Fusion Builder
 * Author: Intense Visions
 * Author URI: https://intensevisions.com/
 * Requires at least: 4.6.0
 * Tested up to: 5.0.3
 *
 * Text Domain: better
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Intense Visions
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !function_exists('BetterCore') ) {
	define( 'BETTERBUILDER_PLUGIN_FILE', __FILE__ );
	define( 'BETTERBUILDER_PLUGIN_FOLDER', dirname( BETTERBUILDER_PLUGIN_FILE ) );
	define( 'BETTERBUILDER_DEBUG', WP_DEBUG );
	require_once('includes/betterbuilder.php');
}

BetterCore( __FILE__ );
