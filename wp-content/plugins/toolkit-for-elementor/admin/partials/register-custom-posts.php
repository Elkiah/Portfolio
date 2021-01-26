<?php
$labels = array(
    'name'               => __( 'Toolkit Templates', 'toolkit-for-elementor' ),
    'singular_name'      => __( 'Toolkit Template', 'toolkit-for-elementor' ),
    'menu_name'          => __( 'Toolkit Templates', 'toolkit-for-elementor' ),
    'name_admin_bar'     => __( 'Toolkit Template', 'toolkit-for-elementor' ),
    'add_new'            => __( 'Add New', 'toolkit-for-elementor' ),
    'add_new_item'       => __( 'Add New', 'toolkit-for-elementor' ),
    'new_item'           => __( 'New Toolkit Template', 'toolkit-for-elementor' ),
    'edit_item'          => __( 'Edit Toolkit Template', 'toolkit-for-elementor' ),
    'view_item'          => __( 'View Templates', 'toolkit-for-elementor' ),
    'all_items'          => __( 'All Toolkit Templates', 'toolkit-for-elementor' ),
    'search_items'       => __( 'Search Toolkit Templates', 'toolkit-for-elementor' ),
    'parent_item_colon'  => __( 'Parent Toolkit Templates:', 'toolkit-for-elementor' ),
    'not_found'          => __( 'No Toolkit Templates found.', 'toolkit-for-elementor' ),
    'not_found_in_trash' => __( 'No Toolkit Templates found in Trash.', 'toolkit-for-elementor' ),
);

$args = array(
    'labels'              => $labels,
    'public'              => true,
    'rewrite'             => false,
    'show_ui'             => true,
    'show_in_menu'        => false,
    'show_in_nav_menus'   => false,
    'exclude_from_search' => true,
    'capability_type'     => 'post',
    'hierarchical'        => false,
    'menu_icon'           => 'dashicons-editor-kitchensink',
    'supports'            => array( 'title', 'thumbnail', 'elementor' ),
);

register_post_type( 'toolkit_template', $args );

?>