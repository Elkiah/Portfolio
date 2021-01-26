<?php
if ( ! class_exists('Lazy_load_Settings' ) ) {
    class Lazy_load_Settings
    {
        private $lazy_load_setting;

        public function __construct()
        {
            $this->lazy_load_setting = new Toolkit_Elementor;
            $this->nonce_key = 'toolkit-elementor';
            ########	REGISTER ACTIVATION HOOK	##########
            register_deactivation_hook(__FILE__, array($this, 'toolkit_performance_deactivation'));
            ########		IS SCRIPT ACTIVE		##########
            $this->toolkit_active = get_option('toolkit_active', '');
            $this->toolkit_license_key = sanitize_text_field(trim(get_option('toolkit_license_key', '')));
            $this->toolkit_license_details = get_option('toolkit_license_details', '');
            $this->toolkit_other_details = get_option('toolkit_other_details', '');
            $this->gtmetrix_location = get_option('toolkit_gtmetrix_location', '');
            $this->gtmetrix_browsers = get_option('toolkit_gtmetrix_browsers', '');
            ########		LICENSE KEY				##########
            $this->time_now = date('Y-m-d H:i:s');
            $this->limit = 5; //BOOSTER PER PAGE
            $this->updateDiff = 10; //IN MINUTES 720 (12 HOURS)
            $this->limitWebsite = 10; //MY LICENSE PER PAGE
            $this->key_verify_url = esc_url_raw('https://toolkitforelementor.com/api/license-verify.php');
            $this->check_updates = esc_url_raw('https://toolkitforelementor.com/api/check-updates.php');
            $this->scan_url = esc_url_raw('https://toolkitforelementor.com/api/gtmetrix-scan.php');
            $this->report_download_url = esc_url_raw('https://toolkitforelementor.com/api/gtmetrix-report-download.php');
            ########	REDIRECT AFTER ACTIVATION	##########
            add_action('admin_init', array($this, 'toolkit_performance_plugin_redirect'));
            add_action('admin_init', array($this, 'admin_inits'));
            add_action('admin_menu', array($this, 'admin_menu'), 502);
            add_action('admin_enqueue_scripts', array($this, "toolkit_enqueue_script"));
            ########		GTMETRIX SCAN CALL				##########
            add_action('wp_ajax_toolkit_performance_gtmetrix_scan', array($this, 'toolkit_performance_gtmetrix_scan'));
            add_action('wp_ajax_nopriv_toolkit_performance_gtmetrix_scan', array($this, 'toolkit_performance_gtmetrix_scan'));
            add_action('wp_ajax_toolkit_performance_gtmetrix_scan_result', array($this, 'toolkit_performance_gtmetrix_scan_result'));
            add_action('wp_ajax_nopriv_toolkit_performance_gtmetrix_scan_result', array($this, 'toolkit_performance_gtmetrix_scan_result'));
            ########		GTMETRIX FULL REPORT DOWNLOAD API CALL				##########
            add_action('wp_ajax_toolkit_performance_gtmetrix_download_report', array($this, 'toolkit_performance_gtmetrix_download_report'));
            add_action('wp_ajax_nopriv_toolkit_performance_gtmetrix_download_report', array($this, 'toolkit_performance_gtmetrix_download_report'));
            ########		GTMETRIX FULL REPORT DOWNLOAD API CALL				##########
            add_action('wp_ajax_toolkit_performance_gtmetrix_download_report', array($this, 'toolkit_performance_gtmetrix_download_report'));
            add_action('wp_ajax_nopriv_toolkit_performance_gtmetrix_download_report', array($this, 'toolkit_performance_gtmetrix_download_report'));
            add_action('wp_ajax_toolkit_performance_gtmetrix_history', array($this, 'toolkit_performance_gtmetrix_history'));
            add_action('wp_ajax_nopriv_toolkit_performance_gtmetrix_history', array($this, 'toolkit_performance_gtmetrix_history'));
            ########		LICENSE KEY VERIFY API CALL				##########
            add_action('wp_ajax_toolkit_license_key_verify', array($this, 'toolkit_license_key_verify'));
			add_action('wp_ajax_toolkit_deactivate_license', array($this, 'toolkit_deactivate_license'));
			add_action( 'toolkit_verify',  array($this, 'toolkit_check_license' ) );
			//add_action( 'wp_ajax_nopriv_toolkit_license_key_verify', array($this,'toolkit_license_key_verify' ));
			########		Widgets Enable/Disable API CALL				##########
			add_action('wp_ajax_disable_wordpress_widgets', array($this, 'disable_wordpress_widgets'));
			add_action( 'widgets_init', array($this, 'set_default_wordpress_widgets'), 100 );
			add_action( 'widgets_init', array($this, 'disable_wordpress_widgets'), 100 );			 
			add_action( 'load-index.php', array($this, 'disable_dashboard_widgets_with_remote_requests') );
			add_action( 'wp_dashboard_setup', array($this, 'dashboard_widgets_toolkit_disable'), 100 );
			add_action( 'wp_network_dashboard_setup', array($this, 'dashboard_widgets_toolkit_disable'), 100 );
			add_action( 'admin_init', array($this, 'get_default_dashboard_widgets'), 100 );
			add_action( 'wp_ajax_dashboard_widgets_toolkit_disable', array($this, 'dashboard_widgets_toolkit_disable'), 100 ); 
			add_action('wp_ajax_disable_elementor_widgets', array($this, 'disable_elementor_widgets'));	
			add_action('elementor/widgets/widgets_registered', array($this , 'toolkit_disable_elementor_elements'), 15);
            ########		SITE DE-ACTIVATION API CALL				##########
            add_action('wp_ajax_toolkit_site_deactivate', array($this, 'toolkit_site_deactivate'));
            add_action('wp_ajax_nopriv_toolkit_site_deactivate', array($this, 'toolkit_site_deactivate'));
            ########			SERVER SETTING TWEAK SAVE				##########
            add_action('wp_ajax_toolkit_server_setting_save', array($this, 'toolkit_server_setting_save'));
            add_action('wp_ajax_toolkit_lazyload_setting_save', array($this, 'toolkit_lazyload_setting_save'));
            add_action('wp_ajax_toolkit_unload_options_save', array($this, 'toolkit_unload_options_save'));

            ########			UPDATE CHECK				##########
            add_action('wp_ajax_toolkit_check_update', array($this, 'toolkit_check_update'));
            add_action('wp_ajax_nopriv_toolkit_check_update', array($this, 'toolkit_check_update'));
        }

        public function admin_inits()
        {
            $this->lazy_load_setting->set_sections($this->get_settings_sections());
            $this->lazy_load_setting->set_fields($this->get_settings_fields());
            $this->lazy_load_setting->admin_init();
        }

        /*****        PLUGIN EXTERNAL STYPE & STYLE REGISTER    *****/
        public function toolkit_enqueue_script()
        {
            global $pagenow;
            $handler = TOOLKIT_FOR_ELEMENTOR_NAME;
            if (isset($_GET['page']) && $_GET['page'] == 'toolkit-performance-tool') {
                wp_enqueue_style($handler, TOOLKIT_FOR_ELEMENTOR_URL . 'admin/css/toolkit-styles.min.css', array());
            }
            wp_enqueue_script($handler, TOOLKIT_FOR_ELEMENTOR_URL . 'admin/js/toolkit-scripts.min.js', array(), false, true);
            wp_localize_script($handler, 'toolkit', array('ajax_url' => admin_url('admin-ajax.php'), 'key_verify_url' => $this->key_verify_url, 'admin_url' => admin_url(), 'site_url' => site_url(), '_nonce' => wp_create_nonce($this->nonce_key)));
        }

        public function getGtmetrixScanHistory($limit = 10, $offset = 0)
        {
            ob_start();
            include_once TOOLKIT_FOR_ELEMENTOR_PATH.'admin/templates/gtmetrix-scan-history.php';
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

        public function toolkit_server_setting_save()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce'])) {
                    if (isset($_POST['gzip_compression'])) {
                        $tweakSetting = array();
                        $server_info = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
                        $is_apache = ($server_info && stripos($server_info, 'apache') !== false) ? true : false;
                        if (!$is_apache) {
                            $tweakSetting['combine_gfonts'] = 'off';
                            $tweakSetting['encoding_header'] = 'off';
                            $tweakSetting['gzip_compression'] = 'off';
                            $tweakSetting['keep_alive'] = 'off';
                            $tweakSetting['ninja_etags'] = 'off';
                            $tweakSetting['leverage_caching'] = 'off';
                            $tweakSetting['expire_headers'] = 'off';
                        } else {
                            $tweakSetting['combine_gfonts'] = isset($_POST['combine_gfonts']) ? $_POST['combine_gfonts'] : 'off';
                            $tweakSetting['encoding_header'] = isset($_POST['encoding_header']) ? $_POST['encoding_header'] : 'off';
                            $tweakSetting['gzip_compression'] = isset($_POST['gzip_compression']) ? $_POST['gzip_compression'] : 'off';
                            $tweakSetting['keep_alive'] = isset($_POST['keep_alive']) ? $_POST['keep_alive'] : 'off';
                            $tweakSetting['ninja_etags'] = isset($_POST['ninja_etags']) ? $_POST['ninja_etags'] : 'off';
                            $tweakSetting['leverage_caching'] = isset($_POST['leverage_caching']) ? $_POST['leverage_caching'] : 'off';
                            $tweakSetting['expire_headers'] = isset($_POST['expire_headers']) ? $_POST['expire_headers'] : 'off';
                        }
                        update_option('toolkit_webserver_tweaks', $tweakSetting);
                        if (function_exists('flush_rewrite_rules')) {
                            flush_rewrite_rules();
                        }
                        $response = array('status' => 1, 'apache' => $is_apache, 'message' => 'Updated Successfully');
                    } else {
                        $settingServer = array();
                        $settingServer['html_minify'] = isset($_POST['html_minify']) ? $_POST['html_minify'] : 'off';
                        $settingServer['css_minify'] = isset($_POST['css_minify']) ? $_POST['css_minify'] : 'off';
                        $settingServer['css_combine'] = isset($_POST['css_combine']) ? $_POST['css_combine'] : 'off';
                        $settingServer['js_minify'] = isset($_POST['js_minify']) ? $_POST['js_minify'] : 'off';
                        $settingServer['js_combine'] = isset($_POST['js_combine']) ? $_POST['js_combine'] : 'off';
                        toolkit_remove_minify_css_js_files();
                        update_option('toolkit_elementor_tweaks', $settingServer);
                        $response = array('status' => 1, 'message' => 'Updated Successfully');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Nonce missing');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }

        public function toolkit_unload_options_save()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST)) {
                    $unloadOpts = array();
                    $unloadOpts['disable_emojis'] = isset($_POST['disable_emojis']) ? $_POST['disable_emojis'] : 'off';
                    $unloadOpts['disable_gutenberg'] = isset($_POST['disable_gutenberg']) ? $_POST['disable_gutenberg'] : 'off';
                    $unloadOpts['disable_commentreply'] = isset($_POST['disable_commentreply']) ? $_POST['disable_commentreply'] : 'off';
                    $unloadOpts['disable_jqmigrate'] = isset($_POST['disable_jqmigrate']) ? $_POST['disable_jqmigrate'] : 'off';
                    $unloadOpts['disable_woohomeajax'] = isset($_POST['disable_woohomeajax']) ? $_POST['disable_woohomeajax'] : 'off';
                    update_option('toolkit_unload_options', $unloadOpts);
                    $response = array('status' => 1, 'message' => 'Updated Successfully.');
                } else {
                    $response = array('status' => 0, 'message' => 'Parameter Missing.');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request.');
            }
            a:
            wp_send_json($response);
            exit();
        }

        public function toolkit_lazyload_setting_save()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce'])) {
                    if (isset($_POST['image']) && isset($_POST['iframe_video'])) {
                        $lazySetting = array();
                        $lazySetting['image'] = isset($_POST['image']) ? $_POST['image'] : 'off';
                        $lazySetting['iframe_video'] = isset($_POST['iframe_video']) ? $_POST['iframe_video'] : 'off';
                        update_option('toolkit_elementor_settings', $lazySetting);
                        $response = array('status' => 1, 'message' => 'Updated Successfully');
                    } else {
                        $response = array('status' => 0, 'message' => 'Invalid request.');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Nonce missing');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }

        /*****        GTMETRIX SCAN CALL    *****/
        public function toolkit_performance_gtmetrix_scan()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce']) && !empty($_POST['scan_url'])) {
                    if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']), $this->nonce_key)) {
                        $license_key = sanitize_text_field(trim($this->toolkit_license_key));
                        if (!$license_key) {
                            $response = array('status' => 0, 'message' => 'License key not verified.');
                            goto a;
                        }
                        global $wpdb;
                        $site_url = site_url();
                        // $site_url = 'http://sproutedweb.com';
                        $myUrl = parse_url($site_url);
                        $myDomain = $myUrl['host'];
                        if (!filter_var(gethostbyname($myDomain), FILTER_VALIDATE_IP) || $myDomain == 'localhost') {
                            // $response = array('status'=>0,'message'=>'Can\'t use on localhost or invalid server.');
                            // goto a;
                        }
                        $scan_url = $_POST['scan_url'];
                        if (!empty($_POST['scan_location'])) {
                            $key = array_search($_POST['scan_location'], array_column($this->gtmetrix_location, 'id'));
                            if (isset($this->gtmetrix_location[$key])) {
                                $region = $this->gtmetrix_location[$key]['name'];
                            } else {
                                $region = 'Default';
                            }
                        } else {
                            $region = 'Default';
                        }
                        if ( ! empty($_POST['scan_browser']) ) {
                            $keyBr = array_search($_POST['scan_browser'], array_column($this->gtmetrix_browsers, 'id'));
                            if (isset($this->gtmetrix_browsers[$keyBr])) {
                                $browser = $this->gtmetrix_browsers[$keyBr]['name'];
                            } else {
                                $browser = 'Default';
                            }
                        } else {
                            $browser = 'Default';
                        }
                        if ( ! trim($license_key) ) {
                            $response = array('status' => 0, 'message' => "License key is not valid.");
                        } else {
                            $postData = array('_nonce' => sanitize_text_field($_POST['_nonce']), 'site_url' => sanitize_text_field($site_url), 'scan_url' => sanitize_text_field($scan_url), 'license_key' => $license_key, 'scan_location' => (int)$_POST['scan_location'], 'region' => $_POST['scan_location'], 'browser' => $_POST['scan_browser']);
                            $url = $this->scan_url;
                            $httpResponse = $this->toolkitwebHTTPPost($url, $postData);
                            $response = $this->toolkitGtmetrixScan($httpResponse, $region, $browser);
                        }
                    } else {
                        $response = array('status' => 0, 'message' => 'Invalid nonce.');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Nonce missing');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }

        /*****        TOOLKIT SITE DE-ACTIVATION API CALL    *****/
        public function toolkit_site_deactivate()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce']) && !empty($_POST['site_url']) && isset($_POST['type']) && isset($_POST['hide_syncer']) && in_array($_POST['type'], array(0, 2))) {
                    if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']), $this->nonce_key)) {
                        $license_key = $this->toolkit_license_key;
                        $site_url = $_POST['site_url'];
                        $gtmetrixUrl = $this->key_verify_url;
                        $postData = array('_nonce' => sanitize_text_field($_POST['_nonce']), 'license_key' => $license_key, 'key_status' => $_POST['type'], 'hide_syncer' => $_POST['hide_syncer'], 'site_url' => sanitize_text_field($site_url), 'date' => date('Y-m-d H:i:s'));
                        $httpResponse = $this->toolkitwebHTTPPost($gtmetrixUrl, $postData);
                        if (is_wp_error($httpResponse)) {
                            $error_message = $httpResponse->get_error_message();
                            $response = array('status' => 0, 'message' => "Something went wrong: $error_message");
                        } else {
                            if (!empty($httpResponse['response']['code']) && $httpResponse['response']['code'] == 200) {
                                $bodyResult = json_decode(wp_remote_retrieve_body($httpResponse), true);
                                if ($bodyResult['status']) {
                                    if (!empty($bodyResult['license_log'])) {
                                        $newLog = '';
                                        global $wpdb;
                                        if (!empty($bodyResult['other_detail']['order_id'])) {
                                            $activatedSites = $wpdb->get_results("SELECT domain FROM {$wpdb->prefix}toolkit_license_log WHERE order_id='" . $bodyResult['other_detail']['order_id'] . "' AND license_key='" . $license_key . "'", ARRAY_A);
                                            $activeLocalDomains = array_column($activatedSites, 'domain');
                                            $activeCurrentDomains = array_column($bodyResult['license_log'], 'domain');
                                            if ($activatedSites) {
                                                $deletedDomainsArr = array_diff($activeLocalDomains, $activeCurrentDomains);
                                                if ($deletedDomainsArr) {
                                                    $domainsStr = "'" . implode("', '", $deletedDomainsArr) . "'";
                                                    $wpdb->query("DELETE FROM {$wpdb->prefix}toolkit_license_log WHERE order_id={$bodyResult['other_detail']['order_id']} AND license_key='{$license_key}' AND domain IN($domainsStr)");
                                                }
                                            }
                                        }
                                        foreach ($bodyResult['license_log'] as $licenseLog) {
                                            $haveLog = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}toolkit_license_log WHERE order_id='" . $licenseLog['order_id'] . "' AND license_key='" . $licenseLog['license_key'] . "' AND domain='" . $licenseLog['domain'] . "'", ARRAY_A);
                                            if ($haveLog && $haveLog['hide_syncer'] != $licenseLog['hide_syncer']) {
                                                $wpdb->update(
                                                    "{$wpdb->prefix}toolkit_license_log",
                                                    array(
                                                        'hide_syncer' => $licenseLog['hide_syncer'],
                                                        'created' => $licenseLog['created'],
                                                        'modified' => $licenseLog['modified']
                                                    ),
                                                    array('id' => $haveLog['id'])
                                                );
                                            }
                                            if (empty($haveLog)) {
                                                $newLog .= ",('" . $licenseLog['order_id'] . "','" . $licenseLog['license_key'] . "','" . $licenseLog['site_url'] . "','" . $licenseLog['domain'] . "','" . $licenseLog['hide_syncer'] . "','" . $licenseLog['created'] . "','" . $licenseLog['modified'] . "')";
                                            }
                                        }
                                        if ($newLog) {
                                            $sql = "INSERT INTO {$wpdb->prefix}toolkit_license_log          (`order_id`,`license_key`,`site_url`,`domain`,`hide_syncer`,`created`,`modified`) VALUES " . ltrim($newLog, ",");
                                            $wpdb->query($sql);
                                        }
                                    }
                                }
                                // print_r($bodyResult['license_log']);
                                $response = $bodyResult;
                            } else {
                                $response = array('status' => 0, 'message' => 'Try Again.');
                            }
                        }
                    } else {
                        $response = array('status' => 0, 'message' => 'Invalid nonce.');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Enter License key');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }
		/*****        Default Wordpress Widgets    *****/
		public function set_default_wordpress_widgets() {
			$widgets = [];
			if ( ! empty( $GLOBALS['wp_widget_factory'] ) ) {
				$widgets = $GLOBALS['wp_widget_factory']->widgets;
			}			
			//update_option('toolkit_wordpress_widgets', $widgets );
		}
		/*****        Disable Wordpress Widgets    *****/
		public function disable_wordpress_widgets() {			
			if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['action']) && $_POST['action'] == 'disable_wordpress_widgets' ) {
				//if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']))) {
					$input = $_POST['toolkit_wp_widget_disable_wordpress'];
					$output  = [];
					$message = null;
					if ( empty( $input ) ) {
						$message = __( 'All wordpress widgets are enabled again.', 'wp-widget-disable' );
					} else {
						// Loop through each of the incoming options.
						foreach ( array_keys( $input ) as $key ) {
							// Check to see if the current option has a value. If so, process it.
							if ( isset( $input[ $key ] ) ) {
								// Strip all HTML and PHP tags and properly handle quoted strings.
								$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
							}
						}
						$output_count = count( $output );
						if ( 1 === $output_count ) {
							$message = __( 'Settings saved. One wordpress widget disabled.', 'wp-widget-disable' );
						} else {
							$message = sprintf(
								/* translators: %d: number of disabled widgets */
								_n(
									'Settings saved. %d wordpress widget disabled.',
									'Settings saved. %d wordpress widgets disabled.',
									number_format_i18n( $output_count ),
									'wp-widget-disable'
								),
								$output_count
							);
						}
					}					
					update_option('toolkit_wp_widget_disable_wordpress',$output);					
					$widgets = (array) get_option( 'toolkit_wp_widget_disable_wordpress', [] );
					if ( ! empty( $widgets ) ) {
						foreach ( array_keys( $widgets ) as $widget_class ) {
							unregister_widget( $widget_class );
						}
					}
					$response = array('status' => 1, 'message' => $message, 'kaila' => $widget_class);
					wp_send_json($response);
					exit();
				//} else {
				//$response = array('status' => 0, 'message' => 'Invalid nonce.');
				//}
			} else {				
				$widgets = (array) get_option( 'toolkit_wp_widget_disable_wordpress', [] );
				if ( ! empty( $widgets ) ) {
					foreach ( array_keys( $widgets ) as $widget_class ) {
						unregister_widget( $widget_class );
					}
				}
            }        
		}
		/*****        Update List Of Elementor Widgets To Disable    *****/
		public function disable_elementor_widgets() {			
			
			if (defined('DOING_AJAX') && DOING_AJAX && $_POST['action'] == 'disable_elementor_widgets' ) {
				//if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']))) {
					$input = $_POST['toolkit_elementor_widgets_disable'];
					$output  = [];
					$message = null;
					if ( empty( $input ) ) {
						$message = __( 'All elementor widgets are enabled again.', 'wp-widget-disable' );
					} else {
						// Loop through each of the incoming options.
						foreach ( array_keys( $input ) as $key ) {
							// Check to see if the current option has a value. If so, process it.
							if ( isset( $input[ $key ] ) ) {
								// Strip all HTML and PHP tags and properly handle quoted strings.
								$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
							}
						}
						$output_count = count( $output );
						if ( 1 === $output_count ) {
							$message = __( 'Settings saved. One elementor widget disabled.', 'wp-widget-disable' );
						} else {
							$message = sprintf(
								/* translators: %d: number of disabled widgets */
								_n(
									'Settings saved. %d elementor widget disabled.',
									'Settings saved. %d elementor widgets disabled.',
									number_format_i18n( $output_count ),
									'wp-widget-disable'
								),
								$output_count
							);
						}
					}					
					update_option('toolkit_elementor_widgets_disable',$output);					
					$response = array('status' => 1, 'message' => $message);
					wp_send_json($response);
					exit();
				//} else {
				//$response = array('status' => 0, 'message' => 'Invalid nonce.');
				//}
			}
		}
		/*****       Disable Elementor Widgets    *****/
		public function toolkit_disable_elementor_elements($widgets_manager) {
			$widgets = (array) get_option( 'toolkit_elementor_widgets_disable', [] );
			if ( ! empty( $widgets ) ) {					
				foreach ( array_keys( $widgets ) as $widget_name ) {
					$widgets_manager->unregister_widget_type($widget_name);
				}					
			}
        }
		/*****        Disable Dashboard Widgets    *****/
		function dashboard_widgets_toolkit_disable() {			
			if (defined('DOING_AJAX') && DOING_AJAX && $_POST['action'] == 'dashboard_widgets_toolkit_disable' ) {
				//if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']))) {
					$input_dash = $_POST['toolkit_wp_widget_disable_dashboard'];
					$output  = [];
					$message_dash = null;
					if ( empty( $input_dash ) ) {
						$message_dash = __( 'All dashboard widgets are enabled again.', 'wp-widget-disable' );
					} else {
						foreach ( array_keys( $input_dash ) as $key ) {
							if ( isset( $input_dash[ $key ] ) ) {
								$output[ $key ] = wp_strip_all_tags( stripslashes( $input_dash[ $key ] ) );
							}
						}
						$output_count = count( $output );
						if ( 1 === $output_count ) {
							$message_dash = __( 'Settings saved. One dashboard widget disabled.', 'wp-widget-disable' );
						} else {
							$message_dash = sprintf(
								_n(
									'Settings saved. %d dashboard widget disabled.',
									'Settings saved. %d dashboard widgets disabled.',
									number_format_i18n( $output_count ),
									'wp-widget-disable'
								),
								$output_count
							);
						}
					}					
					update_option('toolkit_wp_widget_disable_dashboard',$output);					
					$widgets = (array) get_option( 'toolkit_wp_widget_disable_dashboard', [] );
					if ( is_network_admin() ) {
						$widgets = (array) get_site_option( 'toolkit_wp_widget_disable_dashboard', [] );
					}					
					foreach ( $widgets as $widget_id => $meta_box ) {
						if ( 'dashboard_welcome_panel' === $widget_id ) {
							remove_action( 'welcome_panel', 'wp_welcome_panel' );
							continue;
						}
						if ( 'try_gutenberg_panel' === $widget_id ) {
							remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
							continue;
						}
						if ( 'dashboard_browser_nag' === $widget_id || 'dashboard_php_nag' === $widget_id ) {
							// Handled by ::disable_dashboard_widgets_with_remote_requests().
							continue;
						}
						remove_meta_box( $widget_id, get_current_screen()->base, $meta_box );
					}					
					$response_dash = array('status' => 1, 'message' => $message_dash);
					wp_send_json($response_dash);
					exit();
				//} else {
				//$response_dash = array('status' => 0, 'message' => 'Invalid nonce.');
				//}
			} else {				
				$widgets = (array) get_option( 'toolkit_wp_widget_disable_dashboard', [] );
				if ( is_network_admin() ) {
					$widgets = (array) get_site_option( 'toolkit_wp_widget_disable_dashboard', [] );
				}				
				if ( ! $widgets ) {
					return;
				}					
				foreach ( $widgets as $widget_id => $meta_box ) {
					if ( 'dashboard_welcome_panel' === $widget_id ) {
						remove_action( 'welcome_panel', 'wp_welcome_panel' );
						continue;
					}
					if ( 'try_gutenberg_panel' === $widget_id ) {
						remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
						continue;
					}
					if ( 'dashboard_browser_nag' === $widget_id || 'dashboard_php_nag' === $widget_id ) {
						continue;
					}
					remove_meta_box( $widget_id, get_current_screen()->base, $meta_box );
				}				
            }     
		}
		/*****        Disable Dashboard Widgets    *****/		
		public function disable_dashboard_widgets_with_remote_requests() {
            $widgets = [];
			if ( is_network_admin() ) {
				$widgets = (array) get_site_option( 'toolkit_wp_widget_disable_dashboard', [] );
			}
			if ( ! $widgets ) {
				return;
			}
			foreach ( $widgets as $widget_id => $meta_box ) {
				if ( 'dashboard_browser_nag' === $widget_id ) {
					$key = md5( $_SERVER['HTTP_USER_AGENT'] );
					add_filter( 'pre_site_transient_browser_' . $key, '__return_null' );
					continue;
				}
				if ( 'dashboard_php_nag' === $widget_id ) {
					$key = md5( phpversion() );
					add_filter( 'pre_site_transient_php_check_' . $key, '__return_null' );
					continue;
				}
			}
		}
		 /*****        Get All Dashboard Widgets    *****/
		public function get_default_dashboard_widgets() {
			global $wp_meta_boxes;
			$screen = is_network_admin() ? 'dashboard-network' : 'dashboard';			
            $current_screen = get_current_screen();
			if ( ! isset( $wp_meta_boxes[ $screen ] ) || ! is_array( $wp_meta_boxes[ $screen ] ) ) {
				require_once ABSPATH . '/wp-admin/includes/dashboard.php';
				set_current_screen( $screen );
				//$action variable has replaced 'wp_dashboard_setup';
				remove_action( 'wp_dashboard_setup', array($this, 'dashboard_widgets_toolkit_disable'), 100 );
				wp_dashboard_setup();
				add_action( 'wp_dashboard_setup', array($this, 'dashboard_widgets_toolkit_disable'), 100 );
			}
			if ( isset( $wp_meta_boxes[ $screen ][0] ) ) {
				unset( $wp_meta_boxes[ $screen ][0] );
			}
			$widgets = [];
			if ( isset( $wp_meta_boxes[ $screen ] ) ) {
				$widgets = $wp_meta_boxes[ $screen ];
            }
			set_current_screen( $current_screen );
			//echo "<pre>"; print_r($widgets); echo "</pre>"; exit;
			//update_option('toolkit_wp_widget_dashboard_widgets',$widgets);
		}

        /*****        TOOLKIT LICENSE KEY VERFIY API CALL    *****/
        public function toolkit_license_key_verify()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce']) && !empty($_POST['license_key'])) {
                    if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']), $this->nonce_key)) {
                        $license_key = sanitize_text_field(trim($_POST['license_key']));
                        $site_url = site_url();
                        $args = array(
                            'edd_action' => 'activate_license',
                            'license' => $license_key,
                            'item_id' => TOOLKIT_FOR_ELEMENTOR_ITEM_ID,
                            'url' => sanitize_text_field($site_url),
                        );
                        $httpResponse = wp_remote_post(TOOLKIT_FOR_ELEMENTOR_UPDATE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $args));
                        if (is_wp_error($httpResponse)) {
                            $error_message = $httpResponse->get_error_message();
                            $response = array('status' => 0, 'message' => "Something went wrong: $error_message");
                        } else {
                            if (!empty($httpResponse['response']['code']) && $httpResponse['response']['code'] == 200) {
                                $bodyResult = json_decode(wp_remote_retrieve_body($httpResponse), true);
                                if ($bodyResult['success'] && isset($bodyResult['license']) && $bodyResult['license'] == 'valid') {
                                    update_option('toolkit_license_key', $license_key);
                                    update_option('toolkit_license_details', $bodyResult);
									update_option( 'toolkit_license_status', $bodyResult['license'] );
                                    /*update_option('toolkit_other_detail', $bodyResult['other_detail']);
                                    if(!empty($bodyResult['gtmetrix_browsers'])){
                                        update_option('toolkit_gtmetrix_browsers', $this->recursive_sanitize_text_field($bodyResult['gtmetrix_browsers']));
                                    }
                                    if(!empty($bodyResult['gtmetrix_location'])){
                                        update_option('toolkit_gtmetrix_location', $this->recursive_sanitize_text_field($bodyResult['gtmetrix_location']));
                                    }
                                    if(!empty($bodyResult['hq_settings'])){
                                        update_option('toolkit_hq_my_settings', $bodyResult['hq_settings']);
                                    }
                                    if(!empty($bodyResult['hq_version_msg'])){
                                        update_option('toolkit_hq_my_version_msg', $bodyResult['hq_version_msg']);
                                    }
                                    if(!empty($bodyResult['hq_ad_msg'])){
                                        update_option('toolkit_hq_my_ad_msg', $bodyResult['hq_ad_msg']);
                                    }
                                    if(!empty($bodyResult['license_log'])){
                                        $newLog = '';
                                        global $wpdb;
                                        if(!empty($bodyResult['other_detail']['order_id'])){
                                            $activatedSites = $wpdb->get_results("SELECT domain FROM {$wpdb->prefix}toolkit_license_log WHERE order_id='".$bodyResult['other_detail']['order_id']."' AND license_key='".$license_key."'", ARRAY_A);
                                            $activeLocalDomains = array_column($activatedSites, 'domain');
                                            $activeCurrentDomains = array_column($bodyResult['license_log'], 'domain');
                                            if($activatedSites){
                                                $deletedDomainsArr = array_diff($activeLocalDomains,$activeCurrentDomains);
                                                if($deletedDomainsArr){
                                                    $domainsStr = "'" . implode ( "', '", $deletedDomainsArr ) . "'";
                                                    $wpdb->query("DELETE FROM {$wpdb->prefix}toolkit_license_log WHERE order_id={$bodyResult['other_detail']['order_id']} AND license_key='{$license_key}' AND domain IN($domainsStr)");
                                                }
                                            }
                                        }
                                        foreach($bodyResult['license_log'] as $licenseLog){
                                            $haveLog = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}toolkit_license_log WHERE order_id='".$licenseLog['order_id']."' AND license_key='".$licenseLog['license_key']."' AND domain='".$licenseLog['domain']."' AND site_url='".$licenseLog['site_url']."'", ARRAY_A);
                                            if($haveLog && $haveLog['hide_syncer']!=$licenseLog['hide_syncer']){
                                                $update = $wpdb->update(
                                                    "{$wpdb->prefix}toolkit_license_log",
                                                    array(
                                                        'hide_syncer' => $licenseLog['hide_syncer'],
                                                        'created' => $licenseLog['created'],
                                                        'modified' => $licenseLog['modified']
                                                    ),
                                                    array( 'id' => $haveLog['id'] )
                                                );
                                            } else {
                                                $update = 1;
                                            }
                                            if(empty($haveLog)){
                                                $newLog .= ",('".$licenseLog['order_id']."','".$licenseLog['license_key']."','".$licenseLog['site_url']."','".$licenseLog['domain']."','".$licenseLog['hide_syncer']."','".$licenseLog['created']."','".$licenseLog['modified']."')";
                                            }
                                        }
                                        if($newLog){
                                            $sql = "INSERT INTO {$wpdb->prefix}toolkit_license_log(`order_id`,`license_key`,`site_url`,`domain`,`hide_syncer`,`created`,`modified`) VALUES ".ltrim($newLog,",");
                                            $insert = $wpdb->query($sql);
                                        } else {
                                            $insert = 1;
                                        }
                                    }*/
                                    $response = array('status' => 1, 'message' => 'License activated successfully.');
                                } else {
                                    $response = array('status' => 0, 'message' => 'Invalid key, try again.');
                                }
                            } else {
                                $response = array('status' => 0, 'message' => 'Try Again.');
                            }
                        }
                    } else {
                        $response = array('status' => 0, 'message' => 'Invalid nonce.');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Enter License key');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }
		/*****        TOOLKIT Disable License    *****/
		function toolkit_deactivate_license() {

			// listen for our activate button to be clicked
			if (!empty($_POST['_nonce']) && !empty($_POST['license_key'])) {				
				
				// retrieve the license from the database
				$license = trim( $_POST['license_key'] );


				// data to send in our API request
				$api_params = array(
					'edd_action' => 'deactivate_license',
					'license'    => $license,				
					'item_name' => urlencode( 'ToolKit For Elementor' ),
					'url'        => home_url()
				);

				// Call the custom API.
				$response = wp_remote_post( TOOLKIT_FOR_ELEMENTOR_UPDATE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

					if ( is_wp_error( $response ) ) {
						$message = array('status' => 0, 'message' => $response->get_error_message() );
					} else {
						$message = array('status' => 0, 'message' => 'An error occurred, please try again.');
					}					
					exit();
				}

				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// $license_data->license will be either "deactivated" or "failed"
				if( $license_data->license == 'deactivated' ) {
					delete_option( 'toolkit_license_status' );
				}
				$message = array('status' => 1, 'message' => 'Deactivated.');				
				wp_send_json($message);
				exit();
			}
		}
		/*****        TOOLKIT CHECK License API CALL    *****/
		function toolkit_check_license() {
			global $wp_version;

			$license = trim( get_option( 'toolkit_license_key' ) );

			$api_params = array(
				'edd_action' => 'check_license',
				'license' => $license,		
				'item_name' => urlencode( 'ToolKit For Elementor' ),
				'url'       => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( toolkit_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) )
				return false;

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if( $license_data->license == 'valid' ) {
				exit;				
			} else {
				delete_option( 'toolkit_license_status' );
				// this license is deactivated
			}
		}
        /*****        TOOLKIT CHECK UPDATE API CALL    *****/
        public function toolkit_check_update()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce'])) {
                    if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']), $this->nonce_key)) {
                        $site_url = site_url();
                        $license_key = sanitize_text_field(trim($this->toolkit_license_key));
                        $endPoint = $this->check_updates;
                        $postData = array('_nonce' => sanitize_text_field($_POST['_nonce']), 'license_key' => $license_key, 'current_version' => TOOLKIT_FOR_ELEMENTOR_VERSION, 'site_url' => sanitize_text_field($site_url));
                        $httpResponse = $this->toolkitwebHTTPPost($endPoint, $postData);
                        if (is_wp_error($httpResponse)) {
                            $error_message = $httpResponse->get_error_message();
                            $response = array('status' => 0, 'message' => "Something went wrong: $error_message");
                        } else {
                            if (!empty($httpResponse['response']['code']) && $httpResponse['response']['code'] == 200) {
                                $bodyResult = json_decode(wp_remote_retrieve_body($httpResponse), true);
                                if ($bodyResult['status']) {
                                    if (isset($bodyResult['is_active']) && $bodyResult['is_active'] == 0) {
                                        $this->flushToolKitData();
                                    } else {
                                        if (!empty($bodyResult['hq_settings'])) {
                                            update_option('toolkit_hq_my_settings', $bodyResult['hq_settings']);
                                        }
                                        if (!empty($bodyResult['hq_version_msg'])) {
                                            update_option('toolkit_hq_my_version_msg', $bodyResult['hq_version_msg']);
                                        }
                                        if (!empty($bodyResult['hq_ad_msg'])) {
                                            update_option('toolkit_hq_my_ad_msg', $bodyResult['hq_ad_msg']);
                                        }
                                        if (!empty($bodyResult['gtmetrix_browsers'])) {
                                            update_option('toolkit_gtmetrix_browsers', $this->recursive_sanitize_text_field($bodyResult['gtmetrix_browsers']));
                                        }
                                        if (!empty($bodyResult['gtmetrix_location'])) {
                                            update_option('toolkit_gtmetrix_location', $this->recursive_sanitize_text_field($bodyResult['gtmetrix_location']));
                                        }
                                    }
                                }
                                $response = $bodyResult;
                            } else {
                                $response = array('status' => 0, 'message' => 'Try Again.');
                            }
                        }
                    } else {
                        $response = array('status' => 0, 'message' => 'Invalid nonce.');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Enter nonce key');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }

        /*****        GTMETRIX SCAN CALL    *****/
        public function toolkit_performance_gtmetrix_scan_result()
        {
            $this->get_template('gtmetrix-scan');
            echo gt_metrix_settings_display();
            exit();
        }

        public function toolkit_performance_gtmetrix_history()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['_nonce']) && !empty($_POST['page_no'])) {
                    if (wp_verify_nonce(sanitize_text_field($_POST['_nonce']), $this->nonce_key)) {
                        $offset = !empty($_POST['page_no']) ? (($_POST['page_no'] - 1) * $this->limit) : 0;
                        $html = $this->getGtmetrixScanHistory($this->limit, $offset);
                        $response = array('status' => 1, 'message' => 'Successful', 'html' => $html);
                    } else {
                        $response = array('status' => 0, 'message' => 'Invalid nonce');
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Invalid Request');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }

        /*****        GTMETRIX FULL REPORT DOWNLOAD CALL    *****/
        public function toolkit_performance_gtmetrix_download_report()
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                if (!empty($_POST['report_url'] && $_POST['testid'])) {
                    global $wpdb;
                    $postData = array('_nonce' => wp_create_nonce($this->nonce_key), 'report_url' => $_POST['report_url']);
                    $url = $this->report_download_url;
                    $httpResponse = $this->toolkitwebHTTPPost($url, $postData);
                    if (is_wp_error($httpResponse)) {
                        $error_message = $httpResponse->get_error_message();
                        $response = array('status' => 0, 'message' => "Something went wrong: $error_message");
                    } else {
                        if (!empty($httpResponse['response']['code']) && $httpResponse['response']['code'] == 200) {
                            $bodyResult = json_decode(wp_remote_retrieve_body($httpResponse), true);
                            // echo json_decode($bodyResult['report']);
                            if ($bodyResult['status']) {
                                $reportPath = $this->get_plugin_dir() . "admin/gtmetrix/pdf/report_pdf-{$_POST['testid']}.pdf";
                                chmod($this->get_plugin_dir() . "admin/gtmetrix/pdf/", 0777);
                                file_put_contents($reportPath, base64_decode($bodyResult['report']));
                                $response = array('status' => 1, 'message' => 'Successful', 'report' => $bodyResult['report']);
                            } else {
                                $response = array('status' => 0, 'message' => $bodyResult['message']);
                            }
                        } else {
                            $response = array('status' => 0, 'message' => 'Try Again.');
                        }
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Invalid data');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid Request');
            }
            a:
            wp_send_json($response);
            exit();
        }

        public function toolkit_performance_plugin_redirect()
        {
            global $pagenow, $wpdb;
            $otherDetails = $this->toolkit_other_details;
            $otherDetailsGt = get_option('toolkit_gtmetrix_other_details');
            $lastUpdate = get_option('toolkit_last_update');
            $currentTime = $this->time_now;
            $licenceOtherDetail = get_option('toolkit_other_detail');
            $license_key = $this->toolkit_license_key;
            $site_url = site_url();
            if (!empty($license_key) && !empty($licenceOtherDetail['order_id'])) {
                if (!empty($lastUpdate)) {
                    $to_time = strtotime($currentTime);
                    $from_time = strtotime($lastUpdate);
                    $minutesDiff = round(abs($to_time - $from_time) / 60, 2);
                    if ($minutesDiff >= $this->updateDiff) {
                        $site_url = site_url();
                        $license_key = sanitize_text_field(trim($this->toolkit_license_key));
                        $endPoint = $this->check_updates;
                        $postData = array('_nonce' => wp_create_nonce($this->nonce_key), 'license_key' => $license_key, 'current_version' => TOOLKIT_FOR_ELEMENTOR_VERSION, 'site_url' => sanitize_text_field($site_url));
                        $httpResponse = $this->toolkitwebHTTPPost($endPoint, $postData);
                        if (is_wp_error($httpResponse)) {
                            $error_message = $httpResponse->get_error_message();
                            $response = array('status' => 0, 'message' => "Something went wrong: $error_message");
                        } else {
                            if (!empty($httpResponse['response']['code']) && $httpResponse['response']['code'] == 200) {
                                $bodyResult = json_decode(wp_remote_retrieve_body($httpResponse), true);
                                if ($bodyResult['status']) {
                                    if (isset($bodyResult['is_active']) && $bodyResult['is_active'] == 0) {
                                        $this->flushToolKitData();
                                    } else {
                                        if (!empty($bodyResult['hq_settings'])) {
                                            update_option('toolkit_hq_my_settings', $bodyResult['hq_settings']);
                                        }
                                        if (!empty($bodyResult['hq_version_msg'])) {
                                            update_option('toolkit_hq_my_version_msg', $bodyResult['hq_version_msg']);
                                        }
                                        if (!empty($bodyResult['hq_ad_msg'])) {
                                            update_option('toolkit_hq_my_ad_msg', $bodyResult['hq_ad_msg']);
                                        }
                                    }
                                }
                                update_option('toolkit_last_update', $this->time_now);
                            }
                        }
                    }
                }
                $isMySiteActive = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}toolkit_license_log WHERE order_id='" . $licenceOtherDetail['order_id'] . "' AND license_key='" . $license_key . "' AND site_url='{$site_url}'", ARRAY_A);
                if (empty($isMySiteActive)) {
                    $wpdb->query("DELETE FROM {$wpdb->prefix}toolkit_license_log WHERE order_id={$licenceOtherDetail['order_id']} AND license_key='" . $license_key . "'");
                    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}toolkit_license_log");
                    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}toolkit_gtmetrix");
                    delete_option('toolkit_other_detail');
                    //delete_option('toolkit_license_details');
                    //delete_option('toolkit_license_key');
                    delete_option('toolkit_hq_my_settings');
                    delete_option('toolkit_hq_my_version_msg');
                    delete_option('toolkit_hq_my_ad_msg');
                    // array_map('unlink', glob(TOOLKIT_FOR_ELEMENTOR_PATH."admin/screenshots/pdf/*"));
                    // array_map('unlink', glob(TOOLKIT_FOR_ELEMENTOR_PATH."admin/gtmetrix/pdf/*"));
                }
            }
            if (get_option('toolkit_performance_activate', false)) {
                delete_option('toolkit_performance_activate');
                update_option('toolkit_last_update', $this->time_now);
                if (!isset($_GET['activate-multi'])) {
                    wp_redirect(admin_url('admin.php?page=toolkit-performance-tool#toolkit_my_license'));
                }
            }
        }

        public function flushToolKitData()
        {
            global $wpdb;
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}toolkit_license_log");
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}toolkit_gtmetrix");
            //delete_option('toolkit_license_key');
            //delete_option('toolkit_license_details');
            delete_option('toolkit_other_detail');
            delete_option('toolkit_hq_my_settings');
            delete_option('toolkit_hq_my_version_msg');
            delete_option('toolkit_hq_my_ad_msg');
        }

        /*****        PLUGIN DE-ACTIVATION OPTION SAVE    *****/
        public function toolkit_performance_deactivation()
        {
            update_option('toolkit_performance_activate', 0);
        }

        /*****        WP REMOTE POST    *****/
        public function toolkitwebHTTPPost($url, $postData)
        {
            $requestVerify = wp_remote_post($url, array(
                    'method' => 'POST',
                    'timeout' => 600,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'Content-Type: application/json',
                        'Content-Length: ' . count($postData)
                    ),
                    'body' => $postData,
                    'cookies' => array()
                )
            );
            return $requestVerify;
        }

        /*****        GTMETRIX INFO FUNCTION    *****/
        public function toolkitGtmetrixScan($httpResponse, $region = '', $browser = '')
        {
            global $wpdb;
            if (is_wp_error($httpResponse)) {
                $error_message = $httpResponse->get_error_message();
                $response = array('status' => 0, 'message' => "Something went wrong: $error_message");
            } else {
                if (!empty($httpResponse['response']['code']) && $httpResponse['response']['code'] == 200) {
                    $bodyResult = json_decode(wp_remote_retrieve_body($httpResponse), true);
                    if ($bodyResult['status']) {
                        if (empty($bodyResult['results']) || empty($bodyResult['testid'])) {
                            $response = array('status' => 0, 'message' => $bodyResult['message'], 'body' => $bodyResult);
                        } else {
                            $gt = $bodyResult['results'];
                            $other = $bodyResult['other_detail'];
                            $wpdb->insert(
                                "{$wpdb->prefix}toolkit_gtmetrix",
                                array(
                                    'test_id' => $bodyResult['testid'],
                                    'scan_url' => $bodyResult['scan_url'],
                                    'load_time' => $gt['fully_loaded_time'],
                                    'page_speed' => $gt['pagespeed_score'],
                                    'yslow' => $gt['yslow_score'],
                                    'region' => $region,
                                    'browser' => $browser,
                                    'resources' => json_encode($bodyResult['resources']),
                                    'response_log' => json_encode($bodyResult['results']),
                                    'is_free' => $bodyResult['is_free'],
                                    'created' => $this->time_now
                                )
                            );
                            if ($wpdb->insert_id) {
                                // update_option('toolkit_gtmetrix_credit',((int)$other['gtmetrix_credit']));
                                if (!empty($bodyResult['hq_settings'])) {
                                    update_option('toolkit_hq_my_settings', $bodyResult['hq_settings']);
                                }
                                $otherDetails['last_update_time'] = $this->time_now;
                                $otherDetails = array_merge($otherDetails, $bodyResult['other_detail']);
                                update_option('toolkit_gtmetrix_other_details', $this->recursive_sanitize_text_field($otherDetails));
                                if (!empty($bodyResult['screenshot'])) {
                                    $toolkit_uploads = WP_CONTENT_DIR . '/toolkit-reports/';
                                    if( ! file_exists($toolkit_uploads) ){
                                        mkdir($toolkit_uploads, 0777, true);
                                    }
                                    file_put_contents($toolkit_uploads."report_pdf-{$bodyResult['testid']}.pdf", base64_decode($bodyResult['report_pdf_full']));
                                    file_put_contents($toolkit_uploads."screenshot-{$bodyResult['testid']}.jpg", base64_decode($bodyResult['screenshot']));
                                    file_put_contents($toolkit_uploads."yslow-{$bodyResult['testid']}.txt", base64_decode($bodyResult['yslow']));
                                    file_put_contents($toolkit_uploads."pagespeed-{$bodyResult['testid']}.txt", base64_decode($bodyResult['pagespeed']));
                                }
                                $response = array('status' => 1, 'message' => $bodyResult['message'], 'bodyResult' => $bodyResult);
                            } else {
                                $response = array('status' => 0, 'message' => 'Try Again.');
                            }
                        }
                    } else {
                        $response = array('status' => 0, 'message' => $bodyResult['message'], 'is_free' => 1);
                        if (isset($bodyResult['is_free'])) {
                            update_option('toolkit_gtmetrix_credit', 0);
                            $response['is_free'] = 0;
                        }
                    }
                } else {
                    $response = array('status' => 0, 'message' => 'Try Again.');
                }
            }
            return $response;
        }

        public function admin_menu()
        {
            if (current_user_can('use_toolkit_features')) {
                add_submenu_page(Elementor\Settings::PAGE_ID, __('ToolKit For Elementor', 'toolkit-for-elementor'), __('ToolKit For Elementor', 'toolkit-for-elementor'), 'manage_options', 'toolkit-performance-tool', array($this, 'plugin_page'));
                if (!is_elementor_pro_activated()) {
                    add_submenu_page(
                        Elementor\Settings::PAGE_ID,
                        __('Toolkit Templates', 'toolkit-for-elementor'),
                        __('Toolkit Templates', 'toolkit-for-elementor'),
                        'edit_pages',
                        'edit.php?post_type=toolkit_template'
                    );
                }
            }
        }

        public function plugin_page()
        {
            echo '<div class="wrap"><img src="https://toolkitforelementor.com/download/toolkitlogo.png" alt="ToolKit for Elementor" />';
            $this->lazy_load_setting->show_navigation();
            $this->lazy_load_setting->show_forms();
            echo '</div>';
        }

        /*****        RECURSIVE SANITIZE TEXT    *****/
        public function recursive_sanitize_text_field($array)
        {
            if (is_array($array)) {
                foreach ($array as $key => &$value) {
                    if (is_array($value)) {
                        $value = $this->recursive_sanitize_text_field($value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
                }
            }
            return $array;
        }

        /*****        RECURSIVE SANITIZE    *****/
        public function recursive_sanitize_html_field($array)
        {
            if (is_array($array)) {
                foreach ($array as $key => &$value) {
                    if (is_array($value)) {
                        $value = $this->recursive_sanitize_html_field($value);
                    } else {
                        $value = sanitize_text_field(htmlentities($value));
                    }
                }
            }
            return $array;
        }

        public function get_plugin_dir()
        {
            return TOOLKIT_FOR_ELEMENTOR_PATH;
        }

        public function get_template($template)
        {
            $template_name = 'admin/templates/' . $template . '.php';
            require_once TOOLKIT_FOR_ELEMENTOR_PATH . $template_name;
        }

        public function get_plugin_url($url)
        {
            return TOOLKIT_FOR_ELEMENTOR_URL . $url;
        }

        public function maskLicenseKey($key)
        {
            $key = explode('-', $key);
            $mask_key = '';
            foreach ($key as $v => $n) {
                if (count($key) != ($v + 1)) {
                    $mask_key .= '-' . str_repeat("X", strlen($n));
                } else {
                    $mask_key .= '-' . $n;
                }
            }
            return ltrim($mask_key, '-');
        }

        public function gtmetrix_code($value)
        {
            if ($value >= 90) {
                $code = array('code' => 'A', 'color' => '4bb32b');
            } elseif ($value >= 80 && $value < 90) {
                $code = array('code' => 'B', 'color' => '90c779');
            } elseif ($value >= 70 && $value < 80) {
                $code = array('code' => 'C', 'color' => 'd2bf2f');
            } elseif ($value >= 60 && $value < 70) {
                $code = array('code' => 'D', 'color' => 'e4a63d');
            } elseif ($value >= 50 && $value < 60) {
                $code = array('code' => 'E', 'color' => 'ca7c55');
            } else {
                $code = array('code' => 'F', 'color' => 'd62f30');
            }
            return $code;
        }

        public function formatSizeUnits($bytes)
        {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' byte';
            } else {
                $bytes = '0 bytes';
            }
            return $bytes;
        }

        public function get_settings_sections()
        {
            $this->get_template('gtmetrix-scan');
            $this->get_template('my-license');
            $this->get_template('theme-disable');
            $this->get_template('theme-toolkit');
            $this->get_template('syncer-template');
            $sections = array(
                array(
                    'id' => 'toolkit_performance_tool',
                    'title' => __('BOOSTER', 'toolkit-lazy-load-for-elementor'),
                    'desc' => gt_metrix_settings_display(),
                ),
                array(
                    'id' => 'toolkit_my_templates_syncer',
                    'title' => __('SYNCER', 'toolkit-lazy-load-for-elementor'),
                    'desc' => syncer_template_settings_display(),
                ),
                array(
                    'id' => 'toolkit_theme_less',
                    'title' => __('THEMELESS', 'toolkit-lazy-load-for-elementor'),
                    'desc' => theme_disable_settings_display()
                ),
                array(
                    'id' => 'toolkit_theme_toolkit',
                    'title' => __('TOOLBOX', 'toolkit-lazy-load-for-elementor'),
                    'desc' => theme_toolkit_settings_display()
                ),
                array(
                    'id' => 'toolkit_my_license',
                    'title' => __('MY LICENSE', 'toolkit-lazy-load-for-elementor'),
                    'desc' => my_license_settings_display()
                )
            );
            return $sections;
        }

        // BOOSTER Tab
        public function get_settings_fields()
        {
            $settings_fields = array(
                'toolkit_elementor_settings' => array(
                    array(
                        'name' => 'image',
                        'label' => __('Lazy Load Images', 'toolkit-lazy-load-for-elementor'),
                        'type' => 'checkbox',
                        'default' => '',
                    ),
                    array(
                        'name' => 'iframe_video',
                        'label' => __('Lazy Load Iframes & Videos', 'toolkit-lazy-load-for-elementor'),
                        'type' => 'checkbox',
                        'default' => '',
                    ),
                ),
                'toolkit_elementor_tweaks' => array(
                    array(
                        'name' => 'html_minify',
                        'label' => __('HTML Minify', 'toolkit-lazy-load-for-elementor'),
                        'type' => 'checkbox',
                        'default' => '',
                    ),
                    array(
                        'name' => 'css_minify',
                        'label' => __('Minify CSS', 'toolkit-lazy-load-for-elementor'),
                        'type' => 'checkbox',
                        'default' => '',
                    ),
                    array(
                        'name' => 'js_minify',
                        'label' => __('Minify JS', 'toolkit-lazy-load-for-elementor'),
                        'type' => 'checkbox',
                        'default' => '',
                    ),
                ),
            );
            return $settings_fields;
        }
    }

    new Lazy_load_Settings();
}