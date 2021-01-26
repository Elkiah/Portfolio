<?php

$cpt_options = array();
$cpt_templates = better_locate_available_plugin_templates( '/custom-post/post/' );
foreach( $cpt_templates as $key => $cpt_template ) {
    $cpt_options[ $key ] = $cpt_template;
}

// Post types
$post_type_opts = array();
$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
if ( is_array( $post_types ) ) {
    foreach ( $post_types as $post_type ) {
        if ( $post_type != 'attachment' && $post_type != 'product_variation' && $post_type != 'shop_coupon' ) {
            $type = get_post_type_object( $post_type );
            $post_type_opts[ $post_type ] = $type->label;
        }
    }
}

// Taxonomies
$taxonomy_opts = array();
$taxonomy_names = get_object_taxonomies( 'post' );
if ( is_array( $taxonomy_names ) ) {
    foreach ( $taxonomy_names as $taxonomy_name ) {
        $tax_obj = get_taxonomy( $taxonomy_name );
        $taxonomy_opts[ $taxonomy_name ] = $tax_obj->label;
    }
}

// Terms
$term_opts = array();
$categories = get_terms( 'category' );
if ( is_array( $categories ) ) {
    foreach ( $categories as $post_cat ) {
        $term_opts[ $post_cat->slug ] = $post_cat->name;
    }
}

$cpt_options = array();
$cpt_templates = better_locate_available_plugin_templates( '/custom-post/post/' );
foreach( $cpt_templates as $key => $cpt_template ) {
    $cpt_options[ $key ] = $cpt_template;
}

$this->fields = array(
    'post_type' => array(
        'type' => 'post_type',
        'title' => esc_html__( 'Post Type', 'better' ),
        'default' => 'post',
        'options' => $post_type_opts,
        'class' => 'post_type',
    ),
    'taxonomy' => array(
        'type' => 'taxonomy',
        'default' => 'category',
        'title' => esc_html__( 'Taxonomy', 'better' ),
        'options' => $taxonomy_opts,
        'class' => 'taxonomy',
    ),
    'categories' => array(
        'type' => 'terms',
        'title' => esc_html__( 'Category', 'better' ),
        'options' => $term_opts,
        'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'better' ),
        'class' => 'categories',
    ),
    'include_post_ids' => array(
        'type' => 'text',
        'title' => esc_html__( 'Include Post IDs', 'better' ),
        'description' => esc_html__( 'Comma delimited list of post IDs for a set list of posts that you want to show. Leave blank to not limit posts shown.', 'better' ),
    ),
    'columns' => array(
        'type' => 'range',
        'title' => esc_html__( 'Columns', 'better' ),
        'range_settings'  => [
            'min'  => 1,
            'max' => 4,
            'step' => 1
        ],
        'default' => 3,
    ),
    'align' => array(
        'type' => 'select',
        'default' => 'center',
        'title' => esc_html__( 'Width', 'better' ),
        'options' => array(
            '' => esc_html__( 'Default', 'better' ),
            'wide' => esc_html__( 'Wide', 'better' ),
            'full' => esc_html__( 'Full width', 'better' ),
        ),
    ),
    'order' => array(
        'type' => 'select',
        'default' => 'desc',
        'title' => esc_html__( 'Order', 'better' ),
        'options' => array(
            'desc' => esc_html__( 'Descending', 'better' ),
            'asc' => esc_html__( 'Ascending', 'better' ),
        ),
    ),
    'orderby'  => array(
        'type' => 'select',
        'default' => 'date',
        'title' => esc_html__( 'Order by', 'better' ),
        'options' => array(
            'id' => esc_html__( 'Post ID', 'better' ),
            'author' => esc_html__( 'Author', 'better' ),
            'title' => esc_html__( 'Title', 'better' ),
            'date' => esc_html__( 'Date', 'better' ),
            'menu_order' => esc_html__('Menu Order', 'better' ),
            'modified' => esc_html__( 'Modified Date', 'better' ),
            'rand' => esc_html__( 'Random Order', 'better' ),
            'comment_count' => esc_html__( 'Comment Count', 'better' ),
        ),
    ),
    'css_animation' => array(
        'type' => 'select',
        'title' => esc_html__( 'CSS Animation', 'better' ),
        'default' => 'move-up',
        'options' => Better_Shortcode::css_animation(),
    ),
    'exclude_options' => array(
        'type' => 'heading',
        'title' => esc_html__( 'Exclude Options', 'better' ),
        'class' => 'better-param-heading',
        'separator' => 'before',
    ),
    'exclude_categories' => array(
        'type' => 'terms',
        'title' => esc_html__( 'Exclude Category(s)', 'better' ),
        'class' => 'exclude divi_el_type_autocomplete',
        'description' => esc_html__( 'Select categories that you do not want to show.', 'better' ),
    ),
    'exclude_post_ids' => array(
        'type' => 'text',
        'title' => esc_html__( 'Exclude Post IDs', 'better' ),
        'description' => esc_html__( 'Comma delimited list of post IDs that you do not want to show.', 'better' ),
    ),
    'template_options' => array(
        'type' => 'heading',
        'title' => esc_html__( 'Template Options', 'better' ),
        'class' => 'better-param-heading',
        'separator' => 'before',
    ),
    'cpt_template' => array(
        'type' => 'cpt_template',
        'default' => 'grid-with-caption',
        'title' => esc_html__( 'Template', 'better' ),
        'options' => $cpt_options,
    ),
    'shadow' => array(
        'type' => 'yes_no_button',
        'default' => true,
        'title' => esc_html__( 'Enable Shadow', 'better' ),
    ),
    'posts_per_page' => array(
        'type' => 'text',
        'default' => 6,
        'title' => esc_html__( 'Posts Per Page', 'better' ),
        'description' => esc_html__( 'Enter a number (default will be the value you have set in your settings)', 'better' ),
        'group' => esc_html__( 'Display', 'better' ),
    ),
    'post_count' => array(
        'type' => 'text',
        'title' => esc_html__( 'Post Count', 'better' ),
        'description' => esc_html__( 'Enter a number to limit the number of posts shown (leave blank for all to be shown)', 'better' ),
        'group' => esc_html__( 'Display', 'better' ),
    ),
    'gutter' => array(
        'type' => 'range',
        'title' => esc_html__( 'Grid Gutter', 'better' ),
        'description' => esc_html__( 'Controls the gutter of grid. Default 30px', 'better' ),
        'range_settings'  => [
            'min'  => 1,
            'max' => 100,
            'step' => 1
        ],
        'default' => 30,
        'group' => esc_html__( 'Display', 'better' ),
    ),
    'display_post_categories' => array(
        'type' => 'yes_no_button',
        'default' => true,
        'title' => esc_html__( 'Display Post Categories', 'better' ),
        'group' => esc_html__( 'Meta', 'better' ),
    ),
    'display_post_date' => array(
        'type' => 'yes_no_button',
        'default' => false,
        'title' => esc_html__( 'Display Post Date', 'better' ),
        'group' => esc_html__( 'Meta', 'better' ),
    ),
    'display_post_excerpt' => array(
        'type' => 'yes_no_button',
        'default' => false,
        'title' => esc_html__( 'Display Post Excerpt', 'better' ),
        'group' => esc_html__( 'Meta', 'better' ),
    ),
    'display_post_link' => array(
        'type' => 'yes_no_button',
        'default' => false,
        'title' => esc_html__( 'Display Read more Link', 'better' ),
        'group' => esc_html__( 'Meta', 'better' ),
    ),
    'read_more_text'  => array(
        'type' => 'text',
        'default' => 'Read more',
        'title' => esc_html__( 'Customize Read More Text', 'better' ),
        'condition' => array(
            'display_post_link' => 'true'
        ),
        'group' => esc_html__( 'Meta', 'better' ),
    ),
    'display_post_image' => array(
        'type' => 'yes_no_button',
        'default' => true,
        'title' => esc_html__( 'Display Post Image', 'better' ),
        'group' => esc_html__( 'Image', 'better' ),
    ),
    'image_size'  => array(
        'type' => 'select',
        'default' => 'better-post-grid-480x480',
        'title' => esc_html__( 'Image', 'better' ),
        'options' => array(
            'better-post-grid-480x480' => esc_html__( '480x480', 'better' ),
            'better-post-grid-370x250' => esc_html__( '370x250', 'better' ),
            'better-post-grid-370x560' => esc_html__( '370x560', 'better' ),
        ),
        'group' => esc_html__( 'Image', 'better' ),
    ),
    'show_filter' => array(
        'type' => 'yes_no_button',
        'title' => esc_html__( 'Show Filter', 'summa' ),
        'default' => false,
        'group' => esc_html__( 'Filter', 'better' ),
    ),
    'filter_type' => array(
        'type' => 'select',
        'title' => esc_html__( 'Filter Type', 'better' ),
        'default' => 'static',
        'options' => array(
            'static' => esc_html__( 'Static', 'better' ),
            'ajax' => esc_html__( 'Ajax', 'better' ),
        ),
        'group' => esc_html__( 'Filter', 'better' ),
    ),
    'filter_all_text' => array(
        'type' => 'text',
        'title' => esc_html__( 'Filter All Display Text', 'summa' ),
        'default' => esc_html__( 'All', 'summa' ),
        'group' => esc_html__( 'Filter', 'better' ),
    ),
    'pagination' => array(
        'type' => 'select',
        'title' => esc_html__( 'Pagination', 'better' ),
        'options' => array(
            '' => esc_html__( 'No Pagination', 'better' ),
            'loadmore' => esc_html__( 'Button', 'better' ),
            'infinite' => esc_html__( 'Infinite', 'better' ),
        ),
        'group' => esc_html__( 'Pagination', 'better' ),
    ),
);
