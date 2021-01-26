<?php
/**
 * Field Spacing
 */
function better_spacing_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value,
        //'dependency' => vc_generate_dependencies_attributes( $settings )
    );

    return BetterCore()->template( 'visual-composer/field-spacing', null, $data, false );
}
vc_add_shortcode_param( 'better_spacing', 'better_spacing_settings_field', BetterCore()->assets_url . 'js/visual-composer/field-spacing.js' );

/**
 * Field Spacing
 */
function better_range_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value,
        'dependency' => ''
    );

    return BetterCore()->template( 'visual-composer/field-range', null, $data, false );
}
vc_add_shortcode_param( 'better_range', 'better_range_settings_field', BetterCore()->assets_url . 'js/visual-composer/field-range.js' );

/**
 * Field Heading
 */
function better_heading_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value,
        //'dependency' => ''
    );

    return BetterCore()->template( 'visual-composer/field-heading', null, $data, false );
}
vc_add_shortcode_param( 'better_heading', 'better_heading_settings_field' );

/**
 * Field for media upload custom file type
 */
function better_media_upload_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-media-upload', null, $data, false );
}
vc_add_shortcode_param( 'better_media_upload', 'better_media_upload_settings_field', BetterCore()->assets_url . 'js/visual-composer/field-media-upload.js' );

/**
 * Field Template
 */
function better_template_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-template', null, $data, false );
}
vc_add_shortcode_param( 'better_template', 'better_template_settings_field' );

/**
 * Field Icon Source
 */
function better_icon_source_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-icon-source', null, $data, false );
}
vc_add_shortcode_param( 'better_icon_source', 'better_icon_source_settings_field' );

/**
 * Field Icons
 */
function better_icon_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-icon', null, $data, false );
}
vc_add_shortcode_param( 'better_icon', 'better_icon_settings_field' );

/**
 * Field Image size
 */
function better_image_size_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
        'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-image-size', null, $data, false );
}
vc_add_shortcode_param( 'better_image_size', 'better_image_size_settings_field' );

/**
 * Field Image Stock Unsplash
 */
function better_stock_image_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
		'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-stock-image', null, $data, false );
}
vc_add_shortcode_param( 'better_stock_image', 'better_stock_image_settings_field', BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.js' );

/**
 * Field Post types
 */
function better_post_type_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
		'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-post-type', null, $data, false );
}
vc_add_shortcode_param( 'better_post_type', 'better_post_type_settings_field', BetterCore()->assets_url . 'shortcodes/posts/better-post-type.js' );

/**
 * Field Taxonomies
 */
function better_taxonomy_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
		'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-taxonomy', null, $data, false );
}
vc_add_shortcode_param( 'better_taxonomy', 'better_taxonomy_settings_field', BetterCore()->assets_url . 'shortcodes/posts/better-post-type.js' );

/**
 * Field Terms
 */
function better_terms_settings_field( $settings, $value ) {
    $data = array (
        'settings' => $settings,
		'value' => $value
    );

    return BetterCore()->template( 'visual-composer/field-terms', null, $data, false );
}
vc_add_shortcode_param( 'better_terms', 'better_terms_settings_field', BetterCore()->assets_url . 'shortcodes/posts/better-post-type.js' );