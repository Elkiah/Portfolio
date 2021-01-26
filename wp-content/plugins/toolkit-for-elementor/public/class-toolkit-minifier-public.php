<?php
class Toolkit_Minifier_Public {

    function toolkit_concatenate_google_fonts( $buffer ) {

        $buffer_without_comments = preg_replace( '/<!--(.*)-->/Uis', '', $buffer );
        preg_match_all( '/<link(?:\s+(?:(?!href\s*=\s*)[^>])+)?(?:\s+href\s*=\s*([\'"])((?:https?:)?\/\/fonts\.googleapis\.com\/css(?:(?!\1).)+)\1)(?:\s+[^>]*)?>/iU', $buffer_without_comments, $matches );

        if ( ! $matches[2] || 1 === count( $matches ) ) {
            return $buffer;
        }
        $fonts   = array();
        $subsets = array();
        foreach ( $matches[2] as $k => $font ) {
            $font = str_replace( array( '%7C', '%7c' ), '|', $font );
            $font = explode( 'family=', $font );
            $font = ( isset( $font[1] ) ) ? explode( '&', $font[1] ) : array();
            $fonts = array_merge( $fonts, explode( '|', reset( $font ) ) );
            $subset = ( is_array( $font ) ) ? end( $font ) : '';
            if ( false !== strpos( $subset, 'subset=' ) ) {
                $subset  = explode( 'subset=', $subset );
                $subsets = array_merge( $subsets, explode( ',', $subset[1] ) );
            }
            $buffer = str_replace( $matches[0][ $k ], '', $buffer );
        }
        $subsets = ( $subsets ) ? '&subset=' . implode( ',', array_filter( array_unique( $subsets ) ) ) : '';
        $fonts   = implode( '|', array_filter( array_unique( $fonts ) ) );
        $fonts   = str_replace( '|', '%7C', $fonts );
        if ( ! empty( $fonts ) ) {
            $fonts  = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $fonts . $subsets . '" />';
            $buffer = preg_replace( '/<head(.*)>/U', '<head$1>' . $fonts, $buffer, 1 );
        }
        return $buffer;
    }

    //minify css & js starts
    function toolkit_minify_files( $buffer, $extension ) {
        global $wp_scripts, $toolkit_combine_css, $toolkit_combine_js, $toolkit_js_enqueued_in_head;
        if ( 'css' === $extension ) {
            $concatenate = $toolkit_combine_css;
            preg_match_all( apply_filters( 'toolkit_minify_css_regex_pattern', '/<link\s*.+href=[\'|"]([^\'|"]+\.css?.+)[\'|"](.+)>/iU' ), $buffer, $tags_match, PREG_SET_ORDER );
        }
        if ( 'js' === $extension ) {
            $js_files_in_head = array();
            $concatenate      = $toolkit_combine_js;
            if ( $toolkit_js_enqueued_in_head && is_array( $toolkit_js_enqueued_in_head ) ) {
                $js_files_in_head = implode( '|', $toolkit_js_enqueued_in_head );
            }
            preg_match_all( apply_filters( 'toolkit_minify_js_regex_pattern', '#<script[^>]+?src=[\'|"]([^\'|"]+\.js?.+)[\'|"].*>(?:<\/script>)#iU' ), $buffer, $tags_match, PREG_SET_ORDER );
        }
        $original_buffer   = $buffer;
        $files             = array();
        $excluded_files    = array();
        $external_js_files = array();
        foreach ( $tags_match as $tag ) {
            if ( $this->is_toolkit_external_file( $tag[1], $extension ) ) {
                if ( 'js' === $extension && $concatenate ) {
                    $host                 = $this->toolkit_extract_url_component( $tag[1], PHP_URL_HOST );
                    $excluded_external_js = $this->get_toolkit_minify_excluded_external_js();
                    if ( ! isset( $excluded_external_js[ $host ] ) ) {
                        $external_js_files[] = $tag[0];
                    }
                }
                continue;
            }
            if ( $this->is_toolkit_minify_excluded_file( $tag, $extension ) ) {
                if ( $concatenate && 'js' === $extension && false !== strpos( $tag[1], $wp_scripts->registered['jquery-core']->src ) ) {
                    if ( false ) {
                        $external_js_files['jquery-cache-busting'] = str_replace( $tag[1], $tag[1], $tag[0] );
                        $buffer                                    = str_replace( $tag[0], $external_js_files['jquery-cache-busting'], $buffer );
                    } else {
                        $external_js_files[] = $tag[0];
                    }
                    continue;
                }
                $excluded_files[] = $tag;
                continue;
            }
            if ( $concatenate ) {
                if ( 'js' === $extension ) {
                    $file_path = $this->toolkit_clean_exclude_file( $tag[1] );
                    if ( ! empty( $js_files_in_head ) && preg_match( '#(' . $js_files_in_head . ')#', $file_path ) ) {
                        $files['header'][] = strtok( $tag[1], '?' );
                    } else {
                        $files['footer'][] = strtok( $tag[1], '?' );
                    }
                } else {
                    $files[] = strtok( $tag[1], '?' );
                }
                $buffer = str_replace( $tag[0], '', $buffer );
                continue;
            }
            if ( preg_match( '/(?:-|\.)min.' . $extension . '/iU', $tag[1] ) ) {
                $excluded_files[] = $tag;
                continue;
            }
            if ( ! empty( $wp_scripts->registered['jquery-core']->src ) && false !== strpos( $tag[1], $wp_scripts->registered['jquery-core']->src ) ) {
                $excluded_files[] = $tag;
                continue;
            }
            $files[] = $tag;
        }
        if ( empty( $files ) ) {
            return $buffer;
        }
        if ( ! $concatenate ) {
            foreach ( $files as $tag ) {
                $minify_url = $this->get_toolkit_minify_url( $tag[1], $extension );
                if ( ! $minify_url ) {
                    continue;
                }
                $minify_tag = str_replace( $tag[1], $minify_url, $tag[0] );
                if ( 'css' === $extension ) {
                    $minify_tag = str_replace( $tag[2], ' data-minify="1" ' . $tag[2], $minify_tag );
                }
                if ( 'js' === $extension ) {
                    $minify_tag = str_replace( '></script>', ' data-minify="1"></script>', $minify_tag );
                }
                $buffer = str_replace( $tag[0], $minify_tag, $buffer );
            }
            return $buffer;
        }
        if ( 'js' === $extension ) {
            $minify_header_url = '';
            $minify_url = '';
            if( isset($files['header']) ){
                $minify_header_url = $this->get_toolkit_minify_url( $files['header'], $extension );
            }
            if( isset($files['footer']) ){
                $minify_url = $this->get_toolkit_minify_url( $files['footer'], $extension );
            }

            if ( ! $minify_header_url && ! $minify_url ) {
                return $original_buffer;
            }
            foreach ( $external_js_files as $external_js_file ) {
                $buffer = str_replace( $external_js_file, '', $buffer );
            }
            $minify_header_tag = '<script src="' . $minify_header_url . '" data-minify="1"></script>';
            $buffer            = preg_replace( '/<head(.*)>/U', '<head$1>' . implode( '', $external_js_files ) . $minify_header_tag, $buffer, 1 );
            $minify_tag = '<script src="' . $minify_url . '" data-minify="1"></script>';
            return str_replace( '</body>', $minify_tag . '</body>', $buffer );
        }
        if ( 'css' === $extension ) {
            $minify_url = $this->get_toolkit_minify_url( $files, $extension );
            if ( ! $minify_url ) {
                return $original_buffer;
            }
            $minify_tag = '<link rel="stylesheet" href="' . $minify_url . '" data-minify="1" />';
            return preg_replace( '/<head(.*)>/U', '<head$1>' . $minify_tag, $buffer, 1 );
        }
    }

    function create_toolkit_uniqid() {
        return str_replace( '.', '', uniqid( '', true ) );
    }

    function get_toolkit_parse_url( $url ) {
        if ( ! is_string( $url ) ) {
            return;
        }
        $encoded_url = preg_replace_callback(
            '%[^:/@?&=#]+%usD',
            function ( $matches ) {
                return rawurlencode( $matches[0] );
            },
            $url
        );
        $url      = wp_parse_url( $encoded_url );
        $host     = isset( $url['host'] ) ? strtolower( urldecode( $url['host'] ) ) : '';
        $path     = isset( $url['path'] ) ? urldecode( $url['path'] ) : '';
        $scheme   = isset( $url['scheme'] ) ? urldecode( $url['scheme'] ) : '';
        $query    = isset( $url['query'] ) ? urldecode( $url['query'] ) : '';
        $fragment = isset( $url['fragment'] ) ? urldecode( $url['fragment'] ) : '';
        return apply_filters(
            'toolkit_parse_url',
            [
                'host'     => $host,
                'path'     => $path,
                'scheme'   => $scheme,
                'query'    => $query,
                'fragment' => $fragment,
            ]
        );
    }

    function toolkit_url_to_path( $url, $hosts = '' ) {
        $root_dir = trailingslashit( dirname( WP_CONTENT_DIR ) );
        $root_url = str_replace( wp_basename( WP_CONTENT_DIR ), '', content_url() );
        $url_host = wp_parse_url( $url, PHP_URL_HOST );
        if ( null === $url_host ) {
            $subdir_levels = substr_count( preg_replace( '/https?:\/\//', '', site_url() ), '/' );
            $url           = trailingslashit( site_url() . str_repeat( '/..', $subdir_levels ) ) . ltrim( $url, '/' );
        }
        if ( isset( $hosts[ $url_host ] ) && 'home' !== $hosts[ $url_host ] ) {
            $url = str_replace( $url_host, wp_parse_url( site_url(), PHP_URL_HOST ), $url );
        }
        $root_url = preg_replace( '/^https?:/', '', $root_url );
        $url      = preg_replace( '/^https?:/', '', $url );
        $file     = str_replace( $root_url, $root_dir, $url );
        $file     = $this->toolkit_realpath( $file );
        $file = apply_filters( 'toolkit_url_to_path', $file, $url );
        if ( ! $this->toolkit_direct_filesystem()->is_readable( $file ) ) {
            return false;
        }
        return $file;
    }

    function toolkit_realpath( $file ) {
        $path = array();
        foreach ( explode( '/', $file ) as $part ) {
            if ( '' === $part || '.' === $part ) {
                continue;
            }
            if ( '..' !== $part ) {
                array_push( $path, $part );
            }
            elseif ( count( $path ) > 0 ) {
                array_pop( $path );
            }
        }
        $prefix = 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ? '' : '/';
        return $prefix . join( '/', $path );
    }

    function get_toolkit_minify_url( $files, $extension ) {
        if ( empty( $files ) ) {
            return false;
        }
        $hosts         = array();
        $hosts['home'] = $this->toolkit_extract_url_component( home_url(), PHP_URL_HOST );
        $hosts_index   = array_flip( $hosts );
        $minify_key    = get_option( 'toolkit_minify_' . $extension . '_key', '' );
        if( ! $minify_key ){
            update_option( 'toolkit_minify_' . $extension . '_key', $this->create_toolkit_uniqid() );
            $minify_key = get_option( 'toolkit_minify_' . $extension . '_key', '' );
        }
        if ( is_string( $files ) ) {
            $file      = $this->get_toolkit_parse_url( $files );
            $file_path = $this->toolkit_url_to_path( strtok( $files, '?' ), $hosts_index );
            $unique_id = md5( $files . $minify_key );
            $filename  = preg_replace( '/\.(' . $extension . ')$/', '-' . $unique_id . '.' . $extension, ltrim( $this->toolkit_realpath( $file['path'] ), '/' ) );
            $filename = basename($filename);
        } else {
            foreach ( $files as $file ) {
                $file_path[] = $this->toolkit_url_to_path( $file, $hosts_index );
            }
            $files_hash = implode( ',', $files );
            $filename   = md5( $files_hash . $minify_key ) . '.' . $extension;
        }
        $minified_file = TOOLKIT_FOR_ELEMENTOR_MIN_PATH . '/' . $filename;
        if ( ! file_exists( $minified_file ) ) {
            $minified_content = $this->toolkit_minify( $file_path, $extension );
            if ( ! $minified_content ) {
                return false;
            }
            $minify_filepath = $this->toolkit_write_minify_file( $minified_content, $minified_file );
            if ( ! $minify_filepath ) {
                return false;
            }
        }
        $minify_url = TOOLKIT_FOR_ELEMENTOR_MIN_URL . '/' . $filename;
        if ( 'css' === $extension ) {
            return apply_filters( 'toolkit_css_url', $minify_url );
        }
        if ( 'js' === $extension ) {
            return apply_filters( 'toolkit_js_url', $minify_url );
        }
    }

    function toolkit_write_minify_file( $content, $minified_file ) {
        if ( file_exists( $minified_file ) ) {
            return true;
        }
        if ( ! $this->toolkit_mkdir_p( dirname( $minified_file ) ) ) {
            return false;
        }
        return $this->toolkit_put_content( $minified_file, $content );
    }

    function toolkit_put_content( $file, $content ) {
        $chmod = $this->toolkit_get_filesystem_perms( 'file' );
        return $this->toolkit_direct_filesystem()->put_contents( $file, $content, $chmod );
    }

    function toolkit_mkdir_p( $target ) {
        $target = str_replace( '//', '/', $target );
        $target = untrailingslashit( $target );
        if ( empty( $target ) ) {
            $target = '/';
        }
        if ( $this->toolkit_direct_filesystem()->exists( $target ) ) {
            return $this->toolkit_direct_filesystem()->is_dir( $target );
        }
        if ( $this->toolkit_mkdir( $target ) ) {
            return true;
        } elseif ( $this->toolkit_direct_filesystem()->is_dir( dirname( $target ) ) ) {
            return false;
        }
        if ( ( '/' !== $target ) && ( $this->toolkit_mkdir_p( dirname( $target ) ) ) ) {
            return $this->toolkit_mkdir_p( $target );
        }
        return false;
    }


    function toolkit_mkdir( $dir ) {
        $chmod = $this->toolkit_get_filesystem_perms( 'dir' );
        return $this->toolkit_direct_filesystem()->mkdir( $dir, $chmod );
    }

    function toolkit_get_filesystem_perms( $type ) {
        static $perms = [];
        switch ( $type ) {
            case 'dir':
            case 'dirs':
            case 'folder':
            case 'folders':
                $type = 'dir';
                break;

            case 'file':
            case 'files':
                $type = 'file';
                break;

            default:
                return 0755;
        }
        if ( isset( $perms[ $type ] ) ) {
            return $perms[ $type ];
        }
        switch ( $type ) {
            case 'dir':
                if ( defined( 'FS_CHMOD_DIR' ) ) {
                    $perms[ $type ] = FS_CHMOD_DIR;
                } else {
                    $perms[ $type ] = fileperms( ABSPATH ) & 0777 | 0755;
                }
                break;

            case 'file':
                if ( defined( 'FS_CHMOD_FILE' ) ) {
                    $perms[ $type ] = FS_CHMOD_FILE;
                } else {
                    $perms[ $type ] = fileperms( ABSPATH . 'index.php' ) & 0777 | 0644;
                }
        }
        return $perms[ $type ];
    }

    function toolkit_clean_exclude_file( $file ) {
        if ( ! $file ) {
            return false;
        }

        return wp_parse_url( $file, PHP_URL_PATH );
    }

    function is_toolkit_minify_excluded_file( $tag, $extension ) {
        if ( false !== strpos( $tag[0], 'data-minify=' ) || false !== strpos( $tag[0], 'data-no-minify=' ) ) {
            return true;
        }
        if ( 'css' === $extension ) {
            if ( false !== strpos( $tag[0], 'media=' ) && ! preg_match( '/media=["\'](?:["\']|[^"\']*?(all|screen)[^"\']*?["\'])/iU', $tag[0] ) ) {
                return true;
            }
            if ( false !== strpos( $tag[0], 'only screen and' ) ) {
                return true;
            }
        }
        $file_path = $this->toolkit_extract_url_component( $tag[1], PHP_URL_PATH );
        if ( pathinfo( $file_path, PATHINFO_EXTENSION ) !== $extension ) {
            return true;
        }
        $excluded_files = array(); //get_toolkit_exclude_files( $extension );
        if ( ! empty( $excluded_files ) ) {
            foreach ( $excluded_files as $i => $excluded_file ) {
                $excluded_files[ $i ] = str_replace( '#', '\#', $excluded_file );
            }
            $excluded_files = implode( '|', $excluded_files );
            if ( preg_match( '#^(' . $excluded_files . ')$#', $file_path ) ) {
                return true;
            }
        }
        return false;
    }

    function get_toolkit_minify_excluded_external_js() {
        $excluded_external_js = apply_filters(
            'toolkit_minify_excluded_external_js', array(
                'forms.aweber.com',
                'video.unrulymedia.com',
                'gist.github.com',
                'stats.wp.com',
                'stats.wordpress.com',
                'www.statcounter.com',
                'widget.rafflecopter.com',
                'widget-prime.rafflecopter.com',
                'widget.supercounters.com',
                'releases.flowplayer.org',
                'tools.meetaffiliate.com',
                'c.ad6media.fr',
                'cdn.stickyadstv.com',
                'www.smava.de',
                'contextual.media.net',
                'app.getresponse.com',
                'ap.lijit.com',
                'adserver.reklamstore.com',
                's0.wp.com',
                'wprp.zemanta.com',
                'files.bannersnack.com',
                'smarticon.geotrust.com',
                'js.gleam.io',
                'script.ioam.de',
                'ir-na.amazon-adsystem.com',
                'web.ventunotech.com',
                'verify.authorize.net',
                'ads.themoneytizer.com',
                'embed.finanzcheck.de',
                'imagesrv.adition.com',
                'js.juicyads.com',
                'form.jotformeu.com',
                'speakerdeck.com',
                'content.jwplatform.com',
                'ads.investingchannel.com',
                'app.ecwid.com',
                'www.industriejobs.de',
                's.gravatar.com',
                'cdn.jsdelivr.net',
                'cdnjs.cloudflare.com',
                'code.jquery.com',
            )
        );
        return array_flip( $excluded_external_js );
    }

    function is_toolkit_external_file( $url, $extension ) {
        $file       = $this->get_toolkit_parse_url( $url );
        $wp_content = $this->get_toolkit_parse_url( WP_CONTENT_URL );
        $hosts      = array();
        $hosts[]    = $wp_content['host'];
        $hosts_index = array_flip( array_unique( $hosts ) );
        if ( isset( $file['host'] ) && ! empty( $file['host'] ) && ! isset( $hosts_index[ $file['host'] ] ) ) {
            return true;
        }
        if ( ! isset( $file['host'] ) && ! preg_match( '#(' . $wp_content['path'] . '|wp-includes)#', $file['path'] ) ) {
            return true;
        }
        return false;
    }

    function toolkit_extract_url_component( $url, $component ) {
        return _get_component_from_parsed_url_array( wp_parse_url( $url ), $component );
    }

    function toolkit_minify( $files, $extension ) {
        require_once TOOLKIT_FOR_ELEMENTOR_PATH . "includes/vendor/autoload.php";
        require_once TOOLKIT_FOR_ELEMENTOR_PATH . "public/class-toolkit-minify-css-urirewriter.php";
        if ( 'css' === $extension ) {
            $minify = new MatthiasMullie\Minify\CSS();
        } elseif ( 'js' === $extension ) {
            $minify = new MatthiasMullie\Minify\JS();
        }
        $files = (array) $files;
        foreach ( $files as $file ) {
            $file_content = $this->toolkit_direct_filesystem()->get_contents( $file );
            if ( 'css' === $extension ) {
                $document_root = apply_filters( 'toolkit_min_documentRoot', ABSPATH );
                $file_content = $this->toolkit_cdn_css_properties( Toolkit_Minify_CSS_UriRewriter::rewrite( $file_content, dirname( $file ), $document_root ) );
            }
            $minify->add( $file_content );
        }
        $minified_content = $minify->minify();
        if ( empty( $minified_content ) ) {
            return false;
        }
        return $minified_content;
    }

    function toolkit_direct_filesystem() {
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
        return new WP_Filesystem_Direct( new StdClass() );
    }

    function toolkit_cdn_css_properties( $buffer ) {
        $zone   = array(
            'all',
            'images',
            'css_and_js',
            'css',
        );
        $cnames = [];
        $do_toolkit_cdn_css_properties = apply_filters( 'do_toolkit_cdn_css_properties', true );
        if ( ! $cnames || ! $do_toolkit_cdn_css_properties ) {
            return $buffer;
        }
        preg_match_all( '/url\((?![\'"]?data)([^\)]+)\)/i', $buffer, $matches );
        if ( is_array( $matches ) ) {
            $i = 0;
            foreach ( $matches[1] as $url ) {
                $url = trim( $url, " \t\n\r\0\x0B\"'" );
                //$url      = get_toolkit_cdn_url( apply_filters( 'toolkit_cdn_css_properties_url', $url ), $zone );
                $property = str_replace( $matches[1][ $i ], $url, $matches[0][ $i ] );
                $buffer   = str_replace( $matches[0][ $i ], $property, $buffer );
                $i++;
            }
        }
        return $buffer;
    }
}