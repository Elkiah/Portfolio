<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://toolkitforelementor.com
 * @since      1.0.0
 * @author     ToolKit For Elementor <support@toolkitforelementor.com>
 * @package    Toolkit_For_Elementor
 * @subpackage Toolkit_For_Elementor/public
 */

class Toolkit_For_Elementor_Public {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/toolkit-for-elementor-public.js', array( 'jquery' ), $this->version, false );

	}

    function toolkit_template_header_enabled() {
	    if( is_elementor_pro_activated() ){
	        return false;
        }
        $header_id = $this->get_settings( 'type_header' );
        $status    = false;

        if ( '' !== $header_id ) {
            $status = true;
        }

        return apply_filters( 'toolkit_template_header_enabled', $status );
    }

    function toolkit_template_footer_enabled() {
        if( is_elementor_pro_activated() ){
            return false;
        }
        $header_id = $this->get_settings( 'type_footer' );
        $status = false;

        if ( '' !== $header_id ) {
            $status = true;
        }

        return apply_filters( 'toolkit_template_footer_enabled', $status );
    }

    public function get_settings( $setting = '' ) {
        $template = 0;
        if ( 'type_header' == $setting || 'type_footer' == $setting ) {
            $templates = $this->get_template_id( $setting );
            $template = is_array( $templates ) ? $templates[0] : '';
            $template = apply_filters( "toolkit_template_get_settings_{$setting}", $template );
        }
        return $template;
    }

    public function get_template_id( $type ) {
        $cached = wp_cache_get( "toolkit_template_" . $type );
        if ( false !== $cached ) {
            return $cached;
        }
        $args = array(
            'post_type'    => 'toolkit_template',
            'meta_key'     => 'toolkit_template_type',
            'meta_value'   => $type,
            'meta_type'    => 'post',
            'meta_compare' => '>=',
            'orderby'      => 'meta_value',
            'order'        => 'ASC',
            'meta_query'   => array(
                'relation' => 'OR',
                array(
                    'key'     => 'toolkit_template_type',
                    'value'   => $type,
                    'compare' => '==',
                    'type'    => 'post',
                ),
            ),
        );
        $args = apply_filters( 'toolkit_template_get_id_args', $args );
        $template = new WP_Query(
            $args
        );
        if ( $template->have_posts() ) {
            $posts = wp_list_pluck( $template->posts, 'ID' );
            wp_cache_set( "toolkit_template_" . $type, $posts );
            return $posts;
        }
        return '';
    }

    public function render_header(){
	    if( $this->toolkit_template_header_enabled() ){
	        $header_id = $this->get_settings('type_header');
	        if( $header_id > 0 ){
	            $elementor = Elementor\Plugin::instance();
                echo $elementor->frontend->get_builder_content_for_display( $header_id );
            }
        }
    }

    public function render_footer(){
        if( $this->toolkit_template_header_enabled() ){
            $footer_id = $this->get_settings('type_footer');
            if( $footer_id > 0 ){
                $elementor = Elementor\Plugin::instance();
                echo $elementor->frontend->get_builder_content_for_display( $footer_id );
            }
        }
    }

    public function bodytag_custom_code(){
        global $post;
        $bodytag_code = get_option('theme_disable_bodytag_code', '');
        if( $post->post_type != 'toolkit_template' && $bodytag_code ){
            eval(' ?>'.str_replace('\"','"', $bodytag_code).'<?php ');
        }
    }

    public function minify_css_js_fonts(){
	    if( ! is_admin() ){
            ob_start('toolkit_minify_css_js_fonts');
        }
    }

    public function dequeue_woocommerce_cart_fragments(){
        if (is_front_page()){
            wp_dequeue_script('wc-cart-fragments');
        }
    }

    public function disable_emojis(){
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    public function disable_gutenberg_css(){
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
    }

    public function disable_jquery_migrate(){
        wp_dequeue_script( 'jquery-migrate' );
    }

    public function disable_comment_reply(){
        wp_dequeue_script( 'comment-reply' );
    }

}
