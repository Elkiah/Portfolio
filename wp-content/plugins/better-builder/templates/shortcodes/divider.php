<?php
//wp_enqueue_style('better.divider');

$minified = ( BETTERBUILDER_DEBUG ? "" : "min." );

if ( empty( $id ) ) {
	$id = 'divider_' . rand();
}

if ( empty( $primary_color ) ) $primary_color = "#fff";
if ( empty( $secondary_color ) ) $secondary_color = $primary_color;
if ( empty( $tertiary_color ) ) $tertiary_color = $primary_color;

if ( file_exists( BETTERBUILDER_PLUGIN_FOLDER .'/assets/shortcodes/divider/dividers/' . $type . '.php' ) ) {
	require BETTERBUILDER_PLUGIN_FOLDER .'/assets/shortcodes/divider/dividers/' . $type . '.php';

	$css_path = BETTERBUILDER_PLUGIN_FOLDER .'/assets/shortcodes/divider/css/' . $type . '.' . $minified . 'css';

	if ( $type !== '' && file_exists( $css_path ) ) {
		//wp_enqueue_style( 'better.' . $type );
	}
}