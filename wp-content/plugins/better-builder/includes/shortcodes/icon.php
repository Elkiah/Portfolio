<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterSC_Icon extends Better_Shortcode {

	public function register_assets() {
		global $pagenow, $typenow;

		wp_register_script( 'better.selectize', BetterCore()->assets_url . 'js/selectize/dist/js/standalone/selectize.min.js', array( 'jquery' ), '0.9.1' );
		wp_register_style( 'better.selectize', BetterCore()->assets_url . 'js/selectize/dist/css/selectize.default.min.css', null, '0.9.1' );

		wp_register_script( 'better.icon', BetterCore()->assets_url . 'shortcodes/icon/better.icon.js', array('jquery'), '1.0' );
		wp_enqueue_style( 'better.icon' );

		wp_register_script( 'better.icon-field', BetterCore()->assets_url . 'shortcodes/icon/icon-field.js', array( 'jquery' ), BetterCore()->_version );
		wp_register_style( 'better.icon-field', BetterCore()->assets_url . 'shortcodes/icon/css/icon-field.css');

		if ( current_user_can( 'edit_pages' ) && current_user_can( 'edit_posts' ) && is_admin() ) {
			if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
				wp_enqueue_script( 'better.selectize' );
				wp_enqueue_style( 'better.selectize' );
				wp_enqueue_script( 'better.icon-field' );
				wp_enqueue_style( 'better.icon-field' );
			}
		}
	}

	public function map( $builder, $function = 'map_shortcode', $atts = null ) {
		if ( ! method_exists( $builder, $function ) ) return null;

		return $builder->$function( $this,
			array (
				'name' => esc_attr__( 'Icon', 'better' ),
				'shortcode' => 'better_icon',
				'fields' => $this->get_fields()
			),
			$atts
		 );
	}

	function get_svg_path( $source, $type ) {

		$better_cache_dir = Better_Builder::get_cache_folder();

		if ( !is_dir( $better_cache_dir . 'icons' ) ) {
			BetterSC_Icon::get_icon_sources();
		}

		$better_cache_dir = Better_Builder::get_cache_folder( 'icons' );

		if ( file_exists( $better_cache_dir . '/plugin/' . $source . '/' . $type . '.svg' ) ) {
			return $better_cache_dir . '/plugin/' . $source . '/' . $type . '.svg';
		}

		return null;
	}

	function get_svg( $source, $type, $tag = 'symbol' ) {
	    //  This is used if we want to build the colors, height, and width into the returned svg code
	    //  function get_svg( $source, $type, $tag = 'symbol', $height = '', $width = '', $fill = '' ) {
	    $key = str_replace( ' ', '_', $source . '-' . $type );
	    $path = $this->get_svg_path( $source, $type );
	    $svg = '';
	    //$size = '';

	    if ( file_exists( $path ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			$full_svg = $wp_filesystem->get_contents( $path );

			$viewbox = '';
			$svg_start_index = stripos( $full_svg, '<svg' );
			$end_svg_start_index = strripos( $full_svg, '</svg>' );
			$svg_open_tag_end = $end_svg_start_index;

			$found_svg_start = false;
			$found_svg_end = false;

      		if ( $svg_start_index > -1 && $end_svg_start_index > -1 ) {
		        for ( $i = $svg_start_index; $i < $end_svg_start_index; $i++ ) {
					$character = $full_svg[ $i ];

					if ( $found_svg_start ) {
						$svg .= $character;
					}

					if ( !$found_svg_start && $character == '>' ) {
						$found_svg_start = true;
						$svg_open_tag_end = $i;
					}
		        }

	        	$viewbox_start = stripos( $full_svg, 'viewbox', $svg_start_index );

		        if ( $viewbox_start ) {
					$found_viewbox_start = false;

					for ( $i=$viewbox_start; $i < $svg_open_tag_end; $i++ ) {
						$character = $full_svg[ $i ];

						if ( !$found_viewbox_start && ( $character == '"' || $character == "'" ) ) {
							$found_viewbox_start = true;
						} else if ( $found_viewbox_start && ( $character == '"' || $character == "'" ) ) {
							break;
						} else if ( $found_viewbox_start && ( $character != '"' && $character != "'" ) ) {
							$viewbox .= $character;
						}
					}
		        }

        		$svg = '<'.$tag.' id="' . esc_attr( $key ) . '"' . ( $viewbox != '' ? ' viewBox="' . $viewbox . '"' : ' viewBox="0 0 32 32"' ) . '>' . $svg .  '</'.$tag.'>';
      		}

      		unset( $full_svg );
	    }

	    return $svg;
  	}

	public static function get_icon_sources() {
		if ( false === $packs = get_transient( "BetterSC_Icon::get_icon_sources" ) ) {
			$packs = array();

			if ( file_exists( BETTERBUILDER_PLUGIN_FOLDER . '/icons/' ) ) {
				$sources = BetterSC_Icon::get_source_folders( 'plugin', BETTERBUILDER_PLUGIN_FOLDER . '/icons/' );
				$packs = array_merge( $packs, $sources );
			}

			ksort( $packs );

			set_transient( "BetterSC_Icon::get_icon_sources", $packs, 60 * 5 );
		}

		return $packs;
	}

	public static function get_source_folders( $key, $path ) {
	    require_once(ABSPATH .'/wp-admin/includes/file.php'); //the cheat

	    WP_Filesystem();
	    global $wp_filesystem;

	    $results = scandir( $path );
	    $directories = array();

	    $better_cache_dir = Better_Builder::get_cache_folder( 'icons' );

	    if ( !is_dir( $better_cache_dir . $key ) ) {
      		mkdir( $better_cache_dir . $key, 0777, true );
	    }

	    //unzip icon files
	    foreach ( $results as $result ) {
	      if ( $result === '.' or $result === '..' ) continue;

	      if ( substr( $result, -strlen( ".zip" ) ) == ".zip" ) {

	        if ( !is_dir( $better_cache_dir . $key . '/' . str_replace( '.zip', '', $result ) . '/') ) {
	          $unzipfile = unzip_file( $path . $result, $better_cache_dir . $key . '/' );

	          if ( is_wp_error( $unzipfile ) ) {
	            error_log( $unzipfile->get_error_message() );
	          // } else {
	          //   unlink( $path . $result );
	          }
	        }
	      } else if ( is_dir( $path . $result ) ) {
	        $dir_files = scandir( $path . $result );

	        if ( !is_dir( $better_cache_dir . $key . '/' . $result . '/') ) {
	          mkdir( $better_cache_dir . $key . '/' . $result . '/', 0777, true );

	          foreach ($dir_files as $file) {
	            if ( $file === '.' or $file === '..' ) continue;

	            copy( $path . $result . '/' . $file, $better_cache_dir . $key . '/' . $result . '/' . $file);
	          }
	        }
	      }
	    }

	    $path = $better_cache_dir . $key . '/';

	    $results = scandir( $path );

	    foreach ( $results as $result ) {
	        if ( $result === '.' or $result === '..' ) continue;

	        $title = ucwords( str_replace( '-', ' ', $result ) );
	        $license = 'custom';
	        $license_url = '';
	        $author = '';
	        $website = '';
	        $source = '';
	        $name = $result;

	        if ( is_dir( $path . $result ) ) {
          		$pack_info = null;

				if ( file_exists( $path  . $result . '/pack.json' ) ) {
					try {
						$pack_info = json_decode( $wp_filesystem->get_contents( $path  . $result . '/pack.json' ), true );
						$name = isset( $pack_info['name'] ) ? $pack_info['name'] : '';
						$title = isset( $pack_info['title'] ) ? $pack_info['title'] : '';
						$license = isset( $pack_info['license']['type'] ) ? $pack_info['license']['type'] : '';
						$license_url = isset( $pack_info['license']['url'] ) ? $pack_info['license']['url'] : '';
						$author = isset( $pack_info['author'] ) ? $pack_info['author'] : '';
						$website = isset( $pack_info['website'] ) ? $pack_info['website'] : '';
						$source = isset( $pack_info['source'] ) ? $pack_info['source'] : '';
					} catch (Exception $e) {
						$pack_info = null;
					}
				}

	          	$directories[ $name ] = array(
					'name' => $name,
					'title' => $title,
					'author' => $author,
					'license' => $license,
					'license_url' => $license_url,
					'website' => $website,
					'source' => $source,
					'path' => $path  . $result,
					'count' => BetterSC_Icon::get_folder_count( $path . '/' . $result)
	            );
	        }
	    }

	    return $directories;
  	}

	public static function get_source_icons( $source ) {
		if ( false === $icons = get_transient( 'BetterSC_Icon::get_source_icons' . $source )) {
			$icon_files = array();
			$icons = array();

			$better_cache_dir = Better_Builder::get_cache_folder();

			if ( !is_dir( $better_cache_dir . 'icons' ) ) {
				BetterSC_Icon::get_icon_sources();
			}

			$better_cache_dir = Better_Builder::get_cache_folder( 'icons' );

			if ( file_exists( $better_cache_dir . '/plugin/' . $source . '/' ) ) {
				$icon_files = scandir( $better_cache_dir . '/plugin/' . $source . '/' );
			} else {
				BetterSC_Icon::get_icon_sources();
			}

			foreach ( $icon_files as $key => $icon ) {
				if ( $icon != '.' && $icon != '..' && substr( $icon, -strlen( ".svg" ) ) == ".svg" ) {
					$icons[] = str_replace( '.svg', '', $icon );
				}
			}

			set_transient( 'BetterSC_Icon::get_source_icons' . $source, $icons );
		}

		return $icons;
	}

	public static function get_folder_count( $path ) {
		$files = scandir( $path );
		$count = 0;

		foreach ($files as $key => $file) {
			if ( $file != '.' && $file != '..' && substr( $file, -strlen( ".svg" ) ) == ".svg" ) {
					$count++;
			}
		}

		return $count;
  	}

}
