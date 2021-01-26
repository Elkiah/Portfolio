<?php
if( ! function_exists('is_elementor_pro_activated') ){
    function is_elementor_pro_activated(){
        if( ! function_exists('is_plugin_active') ){
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return is_plugin_active('elementor-pro/elementor-pro.php');
    }
}
if( ! function_exists('is_toolkit_for_elementor_activated') ){
    function is_toolkit_for_elementor_activated(){
		update_option('toolkit_license_key', 'valid');
    	update_option('toolkit_license_status', 'valid');
        $toolkit_license_key = sanitize_text_field(trim(get_option( 'toolkit_license_status', '' )));
        return ($toolkit_license_key) ? true : false;
    }
}
if( ! function_exists('get_toolkit_license_log') ){
    function get_toolkit_license_log(){
		global $wpdb;

        $toolkit_license_key = sanitize_text_field(trim(get_option( 'toolkit_license_key', '' )));

		// Return the results
		if ( $toolkit_license_key ){
			return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}toolkit_license_log WHERE license_key='".$toolkit_license_key."'", ARRAY_A);
		} else {
			return [];
		}
    }
}

if( !function_exists('toolkit_minify_css_js_fonts') ){
    function toolkit_minify_css_js_fonts( $buffer ) {
        $serverTweaks = get_option('toolkit_webserver_tweaks', array());
        $minifyTweaks = get_option('toolkit_elementor_tweaks', array());
        if( isset($serverTweaks['combine_gfonts']) && $serverTweaks['combine_gfonts'] == 'on' ||
            isset($minifyTweaks['css_minify']) && $minifyTweaks['css_minify'] == 'on' ||
            isset($minifyTweaks['js_minify']) && $minifyTweaks['js_minify'] == 'on' ) {
            require_once TOOLKIT_FOR_ELEMENTOR_PATH . "public/class-toolkit-minifier-public.php";
            $minifier = new Toolkit_Minifier_Public();
            if( isset($serverTweaks['combine_gfonts']) && $serverTweaks['combine_gfonts'] == 'on' ){
                $buffer = $minifier->toolkit_concatenate_google_fonts($buffer);
            }
            if( isset($minifyTweaks['css_minify']) && $minifyTweaks['css_minify'] == 'on' ){
                $GLOBALS['toolkit_combine_css'] = ( isset($minifyTweaks['css_combine']) && $minifyTweaks['css_combine'] == 'on' ) ? true : false;
                $buffer = $minifier->toolkit_minify_files($buffer, 'css');
            }
            if( isset($minifyTweaks['js_minify']) && $minifyTweaks['js_minify'] == 'on' ){
                $GLOBALS['toolkit_combine_js'] = ( isset($minifyTweaks['js_combine']) && $minifyTweaks['js_combine'] == 'on' ) ? true : false;
                $buffer = $minifier->toolkit_minify_files($buffer, 'js');
            }
        }
        return $buffer;
    }
}

if( !function_exists('toolkit_remove_minify_css_js_files') ){
    function toolkit_remove_minify_css_js_files( $master = false ) {
        if( $master ){
            $files = glob(TOOLKIT_FOR_ELEMENTOR_MASTER_PATH . '/*');
        } else {
            $files = glob(TOOLKIT_FOR_ELEMENTOR_MIN_PATH . '/*');
        }
        if( $files ){
            foreach($files as $file){
                delete_folder_and_content($file);
            }
        }
    }
}

if( !function_exists('delete_folder_and_content') ){
    function delete_folder_and_content($path){
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                delete_folder_and_content(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        } elseif (is_file($path) === true) {
            return unlink($path);
        }
        return false;
    }
}

if( !function_exists('toolkit_enqueue_template_css') ){
    function toolkit_enqueue_template_css() {
        global $post;
        $temp_args = array(
            'post_type' => 'elementor_library',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_elementor_template_type',
                    'value' => $post->post_type,
                )
            )
        );
        $templates = get_posts($temp_args);
        if( $templates ){
            $template_id = $templates[0];
            if( file_exists(WP_CONTENT_DIR . '/uploads/elementor/css/post-' . $template_id . '.css') ){
                $href = WP_CONTENT_URL . '/uploads/elementor/css/post-' . $template_id . '.css';
                wp_enqueue_style( "elementor-post-" . $template_id, $href, array(), false, 'all');
            }
        }
    }
}

if( ! function_exists('toolkit_get_yslow_labels') ){
    function toolkit_get_yslow_labels(){
        $labels = array(
            'ycdn'		=> "Use a Content Delivery Network (CDN)",
            'ynumreq'	=> "Make fewer HTTP requests",
            'yexpires'	=> "Add Expires headers",
            'ymindom'	=> "Reduce the number of DOM elements",
            'yminify'	=> "Minify JavaScript and CSS",
            'ycompress' => "Compress components",
            'ynofilter' => "Avoid AlphaImageLoader filter",
            'yfavicon'	=> "Make favicon small and cacheable",
            'yno404'	=> "Avoid HTTP 404 (Not Found) error",
            'ymincookie' => "Reduce cookie size",
            'ydns'		=> "Reduce DNS lookups",
            'yexpressions' => "Avoid CSS expressions",
            'ydupes'	=> "Remove duplicate JavaScript and CSS",
            'yxhr'		=> "Use GET for AJAX requests",
            'yetags'	=> "UConfigure entity tags (ETags)",
            'yxhrmethod' => "Make AJAX cacheable",
            'yredirects' => "Avoid URL redirects",
            'ycookiefree' => "Use cookie-free domains"
        );
        return $labels;
    }
}

if( ! function_exists('get_toolkit_available_sites') ){
    function get_toolkit_available_sites(){
        $toolkit_license_key = sanitize_text_field(trim(get_option( 'toolkit_license_key', '' )));

		$postData = [
			'key' => $toolkit_license_key,
		];

		$url = 'https://toolkitforelementor.com/wp-json/toolkithq/v1/sites';
		// Return the results
		if ( $toolkit_license_key ){
			// Get the site from remote
            $response = wp_remote_post($url, array(
                    'method' => 'POST',
                    'timeout' => 600,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'body' => $postData,
                    'cookies' => array()
                )
            );

			// Check if is wp error
			if (is_wp_error($response)) {
				return [];
			} else if ($response['body']) {
				// Transform the body into the correct format
				$body = json_decode($response['body'], true);
				return array_map(function($site) {
					$siteName = trim($site['site_name'], '/');

					// Return the data
					return [
						'hide_syncer' => '1',
						'domain' => $siteName,
						'site_url' => 'https://' . $siteName,
					];
				}, $body);
			} else {
				return [];
			}
		} else {
			return [];
		}
    }
}

