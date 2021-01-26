<?php

function better_icon_source_fusion_field( $fields ) {
     $fields[] = array( 'fusion_better_icon_source', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-icon-source.php' );
     return $fields;
}
add_filter( 'fusion_builder_fields', 'better_icon_source_fusion_field' );

function better_icon_fusion_field( $fields ) {
     $fields[] = array( 'fusion_better_icon', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-icon.php' );
     return $fields;
}
add_filter( 'fusion_builder_fields', 'better_icon_fusion_field' );

function better_media_upload_fusion_field( $fields ) {
    $fields[] = array( 'fusion_better_media_upload', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-media-upload.php' );
    return $fields;
}
add_filter( 'fusion_builder_fields', 'better_media_upload_fusion_field' );

// Stock Image
function better_fusion_stock_image_field( $fields ) {
    $fields[] = array( 'better_stock_image', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-stock-image.php' );
    return $fields;
}
add_filter( 'fusion_builder_fields', 'better_fusion_stock_image_field' );

// Field Post types
function better_fusion_post_type_field( $fields ) {
    $fields[] = array( 'better_post_type', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-post-type.php' );
    return $fields;
}
add_filter( 'fusion_builder_fields', 'better_fusion_post_type_field' );

// Field Taxonomies
function better_fusion_taxonomy_field( $fields ) {
    $fields[] = array( 'better_taxonomy', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-taxonomy.php' );
    return $fields;
}
add_filter( 'fusion_builder_fields', 'better_fusion_taxonomy_field' );

// Field Terms
function better_fusion_terms_field( $fields ) {
    $fields[] = array( 'better_terms', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-terms.php' );
    return $fields;
}
add_filter( 'fusion_builder_fields', 'better_fusion_terms_field' );

// Field Terms
function better_fusion_cpt_template_field( $fields ) {
    $fields[] = array( 'better_cpt_template', BETTERBUILDER_PLUGIN_FOLDER . '/templates/fusion/field-cpt-template.php' );
    return $fields;
}
add_filter( 'fusion_builder_fields', 'better_fusion_cpt_template_field' );