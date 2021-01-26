<?php
global $post;
$body_classes = array('theme-disable');
add_action( 'wp_enqueue_scripts', 'toolkit_enqueue_template_css' );

\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-full-width' );
$bodytag_code = get_option('theme_disable_bodytag_code', ''); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <?php wp_head(); ?>
</head>
<body <?php body_class($body_classes); ?>>
<?php

$public = new Toolkit_For_Elementor_Public('toolkit-for-elementor', TOOLKIT_FOR_ELEMENTOR_VERSION);
//insert body tag extra code
if( $post->post_type != 'toolkit_template' && $bodytag_code ){
    eval(' ?>'.str_replace('\"','"', $bodytag_code).'<?php ');
}
//insert header template
$public->render_header();

//load elementor header
if( function_exists('elementor_theme_do_location') ){
    elementor_theme_do_location( 'header' );
}

//render content area
\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();

//load elementor footer
if( function_exists('elementor_theme_do_location') ){
    elementor_theme_do_location( 'footer' );
}
//insert footer template
$public->render_footer();

//wp footer
wp_footer();
?>
</body>
</html>
