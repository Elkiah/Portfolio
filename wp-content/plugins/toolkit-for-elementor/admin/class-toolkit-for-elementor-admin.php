<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://toolkitforelementor.com
 * @since      1.0.0
 *
 * @package    Toolkit_For_Elementor
 * @subpackage Toolkit_For_Elementor/admin
 * @author     ToolKit For Elementor <support@toolkitforelementor.com>
 */
class Toolkit_For_Elementor_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/toolkit-for-elementor-admin.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/toolkit-for-elementor-admin.js', array( 'jquery' ), $this->version, false );
	}

    function extra_user_profile_fields( $user ) {
        require_once('partials/toolkit-for-elementor-user-fields.php');
    }

    function save_extra_user_profile_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        $user = get_userdata($user_id);
        if( isset($_POST['use_toolkit_features']) ){
            $user->add_cap('use_toolkit_features', true);
        } else {
            $user->remove_cap('use_toolkit_features');
        }
    }

    public function register_post_types() {
        require_once('partials/register-custom-posts.php');
    }

    public function register_meta_boxes() {
        add_meta_box(
            'toolkit_template_details_metabox',
            __('Template Details'),
            array( $this, 'toolkit_template_details' ),
            'toolkit_template',
            'normal',
            'high'
        );
    }

    public function toolkit_template_details( $post ){
        include('partials/toolkit_template-metabox-one.php');
    }

    public function save_toolkit_template_details( $post_id ){
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! isset( $_POST['toolkit_template_nonce'] ) || ! wp_verify_nonce( $_POST['toolkit_template_nonce'], 'toolkit_template_nonce' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }
        if ( isset( $_POST['toolkit_template_type'] ) ) {
            update_post_meta( $post_id, 'toolkit_template_type', esc_attr( $_POST['toolkit_template_type'] ) );
        }
        if ( isset( $_POST['toolkit_template_on_canvas'] ) ) {
            update_post_meta( $post_id, 'toolkit_template_on_canvas', esc_attr( $_POST['toolkit_template_on_canvas'] ) );
        } else {
            delete_post_meta( $post_id, 'toolkit_template_on_canvas' );
        }
    }

    public function admin_bar_link( $wp_admin_bar ) {
	    if( ! current_user_can('manage_options') ){
	        return;
        }
        $protocol = (is_ssl()) ? 'https:' : 'http:';
        $current_url = $protocol . "//" . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $args = array(
            'id'    => 'toolkit_page',
            'title' => 'ToolKit Cache',
            'meta'  => array( 'class' => 'toolkit-admin-bar' )
        );
        $wp_admin_bar->add_menu( $args );
        $toolkit_url = add_query_arg('toolkit_clear_cache', '1', $current_url);
        $args = array(
            'parent'=> 'toolkit_page',
            'id'    => 'toolkit_page_one',
            'title' => 'Clear ToolKit Cache',
            'href'  => $toolkit_url,
            'meta'  => array( 'class' => 'toolkit-admin-bar' )
        );
        $wp_admin_bar->add_menu( $args );
        $master_url = add_query_arg('toolkit_clear_master', '1', $current_url);
        $args = array(
            'parent'=> 'toolkit_page',
            'id'    => 'toolkit_page_two',
            'title' => 'Clear Master Cache',
            'href'  => $master_url,
            'meta'  => array( 'class' => 'toolkit-admin-bar' )
        );
        $wp_admin_bar->add_menu( $args );
    }

    public function clear_plugin_cache() {
        if( isset($_GET['toolkit_clear_cache']) && $_GET['toolkit_clear_cache'] == 1 ) {
            $current_url = remove_query_arg('toolkit_clear_cache', $_SERVER['REQUEST_URI']);
            $protocol = (is_ssl()) ? 'https:' : 'http:';
            $current_url = $protocol . "//" . $_SERVER['HTTP_HOST'] . $current_url;
            toolkit_remove_minify_css_js_files();
            wp_redirect($current_url);
            exit;
        }
        if( isset($_GET['toolkit_clear_master']) && $_GET['toolkit_clear_master'] == 1 ){
            $current_url = remove_query_arg('toolkit_clear_master', $_SERVER['REQUEST_URI']);
            $protocol = (is_ssl()) ? 'https:' : 'http:';
            $current_url = $protocol . "//" . $_SERVER['HTTP_HOST'] . $current_url;
            toolkit_remove_minify_css_js_files(true);
            wp_redirect($current_url);
            exit;
        }
    }

    public function detect_plugin_theme_change(){
        toolkit_remove_minify_css_js_files();
    }

    public function elementor_check(){
        if ( ! is_plugin_active('elementor/elementor.php') ) {
            add_action('admin_notices', array($this, 'elementor_check_notice'));
            deactivate_plugins('toolkit/toolkit-for-elementor.php');
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
    }

    public function elementor_check_notice(){
        echo '<div class="notice notice-error"><p>' . __('Please install and activate <a target="_blank" href="https://wordpress.org/plugins/elementor/"><b>Elementor Page Builder</b></a> plugin before activating.') . '</p></div>';
    }

    public function ninja_stack_settings($rules){
        $serverTweaks = get_option('toolkit_webserver_tweaks', array());
        if( isset($serverTweaks['encoding_header']) && $serverTweaks['encoding_header'] == 'on' ){
            ob_start(); ?>
<IfModule mod_headers.c>
<FilesMatch ".(js|css|xml|gz|html|svg)$">
Header append Vary: Accept-Encoding
</FilesMatch>
</IfModule>
            <?php
            $new_rules = ob_get_contents();
            ob_end_clean();
            $rules .= "\n" . $new_rules;
        }
        if( isset($serverTweaks['gzip_compression']) && $serverTweaks['gzip_compression'] == 'on' ){
            ob_start(); ?>
<IfModule mod_deflate.c>
	SetOutputFilter DEFLATE
    <IfModule mod_setenvif.c>
        <IfModule mod_headers.c>
            SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
            RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding
        </IfModule>
    </IfModule>
    <IfModule mod_filter.c>
        AddOutputFilterByType DEFLATE "application/atom+xml" \
                                      "application/javascript" \
                                      "application/json" \
                                      "application/ld+json" \
                                      "application/manifest+json" \
                                      "application/rdf+xml" \
                                      "application/rss+xml" \
                                      "application/schema+json" \
                                      "application/vnd.geo+json" \
                                      "application/vnd.ms-fontobject" \
                                      "application/x-font-ttf" \
                                      "application/x-font-opentype" \
                                      "application/x-font-truetype" \
                                      "application/x-javascript" \
                                      "application/x-web-app-manifest+json" \
                                      "application/xhtml+xml" \
                                      "application/xml" \
                                      "font/eot" \
                                      "font/opentype" \
                                      "font/otf" \
                                      "image/bmp" \
                                      "image/svg+xml" \
                                      "image/vnd.microsoft.icon" \
                                      "image/x-icon" \
                                      "text/cache-manifest" \
                                      "text/css" \
                                      "text/html" \
                                      "text/javascript" \
                                      "text/plain" \
                                      "text/vcard" \
                                      "text/vnd.rim.location.xloc" \
                                      "text/vtt" \
                                      "text/x-component" \
                                      "text/x-cross-domain-policy" \
                                      "text/xml"

    </IfModule>
    <IfModule mod_mime.c>
        AddEncoding gzip              svgz
    </IfModule>
</IfModule>
          <?php
            $new_rules = ob_get_contents();
            ob_end_clean();
            $rules .= "\n" . $new_rules;
        }
        if( isset($serverTweaks['keep_alive']) && $serverTweaks['keep_alive'] == 'on' ){
            ob_start(); ?>
<ifModule mod_headers.c>
Header set Connection keep-alive
</ifModule>
            <?php
            $new_rules = ob_get_contents();
            ob_end_clean();
            $rules .= "\n" . $new_rules;
        }
        if( isset($serverTweaks['ninja_etags']) && $serverTweaks['ninja_etags'] == 'on' ){
            ob_start(); ?>
<IfModule mod_headers.c>
Header unset ETag
</IfModule>
            <?php
            $new_rules = ob_get_contents();
            ob_end_clean();
            $rules .= "\n" . $new_rules;
        }
        if( isset($serverTweaks['expire_headers']) && $serverTweaks['expire_headers'] == 'on' ){
            ob_start(); ?>
<IfModule mod_expires.c>
ExpiresActive on
ExpiresDefault "access plus 1 week"
ExpiresByType text/css "access plus 1 week"
ExpiresByType application/atom+xml "access plus 1 hour"
ExpiresByType application/rss+xml "access plus 1 hour"
ExpiresByType application/json "access plus 0 seconds"
ExpiresByType application/ld+json "access plus 0 seconds"
ExpiresByType application/schema+json "access plus 0 seconds"
ExpiresByType application/xml "access plus 0 seconds"
ExpiresByType text/xml "access plus 0 seconds"
ExpiresByType image/x-icon "access plus 1 year"
ExpiresByType text/html "access plus 0 seconds"
ExpiresByType application/javascript "access plus 1 week"
ExpiresByType application/x-javascript "access plus 1 week"
ExpiresByType application/js "access plus 1 week"
ExpiresByType text/javascript "access plus 1 week"
ExpiresByType application/manifest+json "access plus 1 week"
ExpiresByType application/x-web-app-manifest+json "access plus 0 seconds"
ExpiresByType text/cache-manifest "access plus 0 seconds"
ExpiresByType text/markdown "access plus 0 seconds"
ExpiresByType audio/ogg "access plus 1 week"
ExpiresByType image/bmp "access plus 1 week"
ExpiresByType image/gif "access plus 1 week"
ExpiresByType image/jpeg "access plus 1 week"
ExpiresByType image/jpg "access plus 1 week"
ExpiresByType image/png "access plus 1 week"
ExpiresByType image/svg+xml "access plus 1 week"
ExpiresByType image/webp "access plus 1 week"
ExpiresByType video/mp4 "access plus 1 week"
ExpiresByType video/ogg "access plus 1 week"
ExpiresByType video/webm "access plus 1 week"
ExpiresByType font/collection "access plus 1 month"
ExpiresByType font/eot "access plus 1 month"
ExpiresByType font/opentype "access plus 1 month"
ExpiresByType font/otf "access plus 1 month"
ExpiresByType application/x-font-ttf "access plus 1 month"
ExpiresByType font/ttf "access plus 1 month"
ExpiresByType application/font-woff "access plus 1 month"
ExpiresByType application/x-font-woff "access plus 1 month"
ExpiresByType font/woff "access plus 1 month"
ExpiresByType application/font-woff2 "access plus 1 month"
ExpiresByType font/woff2 "access plus 1 month"
</IfModule>
            <?php
            $new_rules = ob_get_contents();
            ob_end_clean();
            $rules .= "\n" . $new_rules;
        }

        return $rules;
    }

}
