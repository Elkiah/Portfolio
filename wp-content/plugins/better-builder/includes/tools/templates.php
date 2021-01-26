<?php
/**
 * Functions related to templates
 */

function better_get_plugin_template_name( $path ) {
	if ( false === ( $name = wp_cache_get( 'better_get_plugin_template_name_' . $path ) ) ) {
		$file_data = get_file_data( $path, array('BetterTemplateName' => 'Better Template Name') );

		if ( !empty( $file_data['BetterTemplateName'] ) ) {
			$name = $file_data['BetterTemplateName'];
		} else {
			$name = str_replace( ".php", "", wp_basename( $path ) );
		}

		wp_cache_add( 'better_get_plugin_template_name_' . $path, $name );
	}

	return $name;
}

function better_locate_available_plugin_templates( $relative_path ) {
	if ( false === $templates = wp_cache_get( 'better_locate_available_plugin_templates' . $relative_path ) ) {
		$plugin_files = array();
		$theme_files = array();
		$child_theme_files = array();
		$templates = array();
		$template_directory = get_template_directory();
		$stylesheet_directory = get_stylesheet_directory();

		$search_paths = array(
			'child-theme' => $stylesheet_directory . '/betterbuilder/templates',
			'theme' => $template_directory . '/betterbuilder/templates',
		);

		$search_paths = apply_filters( 'betterbuilder/templates/search_paths', $search_paths );

		// Search Better last
		$search_paths['betterbuilder'] = BETTERBUILDER_PLUGIN_FOLDER . '/templates';

		foreach ( $search_paths as $key => $search_path ) {
			$files = glob( $search_path . $relative_path . '*.php' );

			if ( is_array( $files ) ) {
				foreach ($files  as $filename ) {
	 	 			$template_name = better_get_plugin_template_name( $filename );
	 	 			$template_file = wp_basename( $filename );
	 	 			$templates[ str_replace( '.php', '', $template_file ) ] = $template_name;
				}
			}
		}

		natsort( $templates );

		$templates = array_filter( $templates );

		wp_cache_add( 'better_locate_available_plugin_templates' . $relative_path, $templates );
	}

	return $templates;
}
