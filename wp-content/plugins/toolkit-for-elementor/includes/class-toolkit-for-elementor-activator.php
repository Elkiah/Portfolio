<?php

/**
 * Fired during plugin activation
 *
 * @link       https://toolkitforelementor.com
 * @since      1.0.0
 *
 * @package    Toolkit_For_Elementor
 * @subpackage Toolkit_For_Elementor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Toolkit_For_Elementor
 * @subpackage Toolkit_For_Elementor/includes
 * @author     ToolKit For Elementor <support@toolkitforelementor.com>
 */
class Toolkit_For_Elementor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	    //assign capability to administrator for use of Toolkit
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
        $administrator = $wp_roles->get_role('administrator');
        $administrator->add_cap('use_toolkit_features', true);

		if ( did_action( 'elementor/loaded' ) ) {
			add_option('toolkit_performance_activate', true);
			delete_option('toolkit_performance_activate');
			add_option('toolkit_performance_activate', 1);
			$gtMetrixLog = get_option( 'toolkit_gtmetrix_log');
			$gtMetrixCredit = get_option( 'toolkit_gtmetrix_credit');
			if(!$gtMetrixLog && !$gtMetrixCredit){
				require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/elementor-class.php';
				require_once TOOLKIT_FOR_ELEMENTOR_PATH .'includes/settings.php';
				$obj = new Lazy_load_Settings();
				add_option('toolkit_gtmetrix_credit', $obj->free_scan);
			}
		}
		global $table_prefix, $wpdb;
		$gtmetrix_table = $table_prefix . "toolkit_gtmetrix";
		
		#Check to see if the table exists already, if not, then create it
		if($wpdb->get_var( "SHOW TABLES LIKE '$gtmetrix_table'" ) != $gtmetrix_table)
		{
			$sql = "CREATE TABLE IF NOT EXISTS `$gtmetrix_table` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `test_id` varchar(100) NOT NULL,
					  `scan_url` text NOT NULL,
					  `load_time` varchar(10) NOT NULL,
					  `page_speed` varchar(10) NOT NULL,
					  `yslow` varchar(10) NOT NULL,
					  `region` varchar(200) NOT NULL,
					  `browser` varchar(200) NOT NULL,
					  `response_log` longtext NOT NULL,
					  `resources` longtext NOT NULL,
					  `is_free` tinyint(4) NOT NULL,
					  `created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($sql);
		} else {
			if(!$wpdb->get_var("SHOW COLUMNS FROM `$gtmetrix_table` LIKE 'scan_url';")){
				$sql = "ALTER TABLE `$gtmetrix_table` ADD `scan_url` TEXT NOT NULL  AFTER `test_id`;";
				$wpdb->query($sql);
			}
		}
		########### 	LICENSE LOG TABLE CREATE	##########
		$license_log_table = $table_prefix . "toolkit_license_log";
		if($wpdb->get_var( "SHOW TABLES LIKE '$license_log_table'" ) != $license_log_table)
		{
			$logTable = "CREATE TABLE IF NOT EXISTS `$license_log_table` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `order_id` bigint(20) NOT NULL,
					  `license_key` varchar(100) NOT NULL,
					  `site_url` varchar(200) NOT NULL,
					  `domain` varchar(200) NOT NULL,
					  `hide_syncer` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>''show'',2=>''Hide''',
					  `created` datetime NOT NULL,
					  `modified` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($logTable);
		}
		########### 	RECOMMENDED PLUGIN TABLE CREATE	##########
		$recommended_plugin_table = $table_prefix . "toolkit_recommended_plugin";
		if($wpdb->get_var( "SHOW TABLES LIKE '$recommended_plugin_table'" ) != $recommended_plugin_table)
		{
			$logTable = "CREATE TABLE IF NOT EXISTS `$recommended_plugin_table` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `post_id` bigint(20) NOT NULL,
					  `title` varchar(250) NOT NULL,
					  `logo` varchar(255) NOT NULL,
					  `description` longtext NOT NULL,
					  `link` text NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($logTable);
		}
	}
}
