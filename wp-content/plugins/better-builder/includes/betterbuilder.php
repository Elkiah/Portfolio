<?php

require_once( 'plugins/typerocket/init.php' );

// Load plugin class files
require_once( 'class-betterbuilder.php' );
require_once( 'class-betterbuilder-builder.php' );
require_once( 'class-betterbuilder-builders.php' );
require_once( 'class-betterbuilder-shortcode.php' );
require_once( 'class-betterbuilder-shortcodes.php' );
require_once( 'class-betterbuilder-custom-post-types.php' );
require_once( 'class-betterbuilder-font-loader.php' );

require_once( 'tools/ajax.php' );
require_once( 'tools/posts.php' );
require_once( 'tools/coalesce.php' );
require_once( 'tools/templates.php' );
require_once( 'tools/colors.php' );

function BetterCore ( $file = '' ) {
	$instance = BetterCore::instance( BETTERBUILDER_PLUGIN_FILE, '1.0.0' );

	// Register individual plugins
	if ( !empty( $file ) ) {
		$instance->register_plugin( $file );
	}

	if ( is_null( $instance->settings ) ) {
		$instance->shortcodes = Better_Shortcodes::instance( $instance );
		$instance->builders = Better_Builders::instance( $instance );
		$instance->custom_post_types = Better_Custom_Post_Types::instance( $instance );
	}

	return $instance;
}
