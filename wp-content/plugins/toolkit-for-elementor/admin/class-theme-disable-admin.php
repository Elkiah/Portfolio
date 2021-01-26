<?php
if( ! class_exists('Theme_Disable_Admin') ) {

    class Theme_Disable_Admin{

        public function __construct(){
            add_action('wp_ajax_theme_disable_settings', array($this, 'save_settings'));
            add_action('wp_ajax_theme_toolkit_settings', array($this, 'save_settings'));
            $disable = get_option('theme_disable_themeless', 'no');
            if( $disable == 'yes' ) {
                add_action('admin_enqueue_scripts', array($this, 'enqueue_disable_styles'));
                add_action('admin_footer', array($this, 'disable_themes_content'));
            }
        }

        public function enqueue_disable_styles(){
            wp_enqueue_style('theme-disable-admin', TOOLKIT_FOR_ELEMENTOR_URL . 'admin/css/theme-disable-admin.css', array(), '', 'all');
        }

        public function save_settings(){
            if (isset($_POST['themeless']) && !empty(trim($_POST['themeless']))) {
                update_option('theme_disable_themeless', esc_sql($_POST['themeless']));
                $response = array('status'=>1,'message'=>'Updated Successfully');
            } elseif ( isset($_POST['header_code']) && isset($_POST['footer_code']) && isset($_POST['bodytag_code']) ) {
                update_option('theme_disable_header_code', $_POST['header_code']);
                update_option('theme_disable_footer_code', $_POST['footer_code']);
                update_option('theme_disable_bodytag_code', $_POST['bodytag_code']);
                $response = array('status'=>1,'message'=>'Updated Successfully');
            } else {
                $response = array('status'=>0,'message'=>'Invalid Request');
            }
            wp_send_json( $response );
            exit();
        }

        public function disable_themes_content(){
            $current_screen = get_current_screen();
            if ($current_screen->id != "themes") {
                return;
            } ?>
            <div class="theme-disable-overlay">
                <h3><?php _e("Congratulations on going Themeless!"); ?></h3>
                <?php if( is_elementor_pro_activated() ){ ?>
                    <p><?php _e("Elementor Pro Detected (Yay!) Please proceed to Elementor's Theme Builder to begin building out your Header, Footer and Page/Post templates."); ?></p>
                    <a href="<?php echo admin_url("edit.php?post_type=elementor_library#add_new"); ?>"><?php _e("Awesome, Let's Proceed"); ?></a>
                <?php } else { ?>
                    <p><b><?php _e("We detect that Elementor Pro is not currently installed and/or activated."); ?></b><br /><br />
                    <?php _e("Please be advised that Themeless was designed to work with Elementor Pro's Theme Builder."); ?><br />
		    <?php _e("For Elementor Free users, we have included a method of assigning headers & footers however custom css, third-party plugins will likely be needed."); ?></p>
					
                    <a id="themeless_tab_link" href="<?php echo admin_url("edit.php?post_type=toolkit_template"); ?>"><?php _e("Ok, I Understand. Let's Proceed"); ?></a>
                <?php } ?>
            </div>
        <?php }

        public function edd_plugin_updater() {
            $active_plugins = get_option( 'active_plugins' );
            foreach ( $active_plugins as $active_plugin ) {
                if ( false !== strpos( $active_plugin, TOOLKIT_FOR_ELEMENTOR_NAME ) ) {
                    $plugin_name = $active_plugin;
                    break;
                }
            }
            if ( ! $plugin_name ) {
                return;
            }

            $license_key = trim( get_option( 'toolkit_license_key' ) );
            if ( ! $license_key ) {
                return;
            }

            if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
                include( TOOLKIT_FOR_ELEMENTOR_PATH . '/admin/EDD_SL_Plugin_Updater.php' );
            }
            $edd_updater = new EDD_SL_Plugin_Updater(
                TOOLKIT_FOR_ELEMENTOR_UPDATE_URL,
                TOOLKIT_FOR_ELEMENTOR_FILE,
                array(
                    'version' => TOOLKIT_FOR_ELEMENTOR_VERSION,
                    'license' => $license_key,
                    'item_id' => TOOLKIT_FOR_ELEMENTOR_ITEM_ID,
                    'author'  => TOOLKIT_FOR_ELEMENTOR_AUTHOR,
                    'beta'    => false,
                )
            );

        }
					
    }

}
