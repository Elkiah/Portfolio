<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://toolkitforelementor.com
 * @since      1.0.0
 *
 * @package    Toolkit_For_Elementor
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$widget_disable_options_all = [
	'toolkit_wp_widget_disable_dashboard',
	'toolkit_wp_widget_disable_sidebar',
	'toolkit_elementor_widgets_disable',
];
$widget_disable_options = [
	'toolkit_wp_widget_disable_dashboard',
	'toolkit_wp_widget_disable_sidebar',
];

if ( ! is_multisite() ) {
	foreach ( $widget_disable_options_all as $widget_disable_option ) {
		delete_option( $widget_disable_option );
	}
} else {
	global $wpdb;

	$widget_disable_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $widget_disable_ids as $widget_disable_id ) {
		switch_to_blog( $widget_disable_id );

		foreach ( $widget_disable_options as $widget_disable_option ) {
			delete_option( $widget_disable_option );
		}

		restore_current_blog( $widget_disable_id );
	}
	delete_option( 'toolkit_elementor_widgets_disable' );
}
