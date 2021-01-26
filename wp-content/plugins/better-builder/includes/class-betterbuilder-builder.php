<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Better_Builder {

	public static function get_cache_folder( $path = '' ) {
		if ( false === $better_cache_dir = wp_cache_get( 'Better_Builder::get_cache_folder' . $path ) ) {
			$uploads = wp_upload_dir();
			$better_cache_dir = $uploads['basedir'] . '/better-cache';
			$better_cache_dir = apply_filters( 'better_cache_folder', $better_cache_dir );

			if ( !file_exists( $better_cache_dir ) ) {
				mkdir( $better_cache_dir, 0777, true );
			}

			$better_cache_dir = trailingslashit( $better_cache_dir . '/' . $path );

			if ( !file_exists( $better_cache_dir )  ) {
				mkdir( $better_cache_dir, 0777, true );
			}

			wp_cache_add( 'Better_Builder::get_cache_folder' . $path, $better_cache_dir );
		}

		return $better_cache_dir;
	}

}
