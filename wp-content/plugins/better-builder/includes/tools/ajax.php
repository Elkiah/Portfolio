<?php
add_action( 'wp_ajax_better_generic_post_action', 'better_generic_post_action' );
add_action( 'wp_ajax_nopriv_better_generic_post_action', 'better_generic_post_action' );

function better_generic_post_action() {

	// check for rights
	if ( ! current_user_can( 'edit_pages' ) && ! current_user_can( 'edit_posts' ) )
		die( __( "You are not allowed to be here" ) );

	if ( ! empty( $_POST["postType"] ) ) {
		//check_ajax_referer( 'better-plugin-noonce', 'security' );
		$post_type = strtolower( wp_filter_nohtml_kses( $_POST["postType"] ) );

		$post_type_info['post_type'] = $post_type;		

		$post_type_info['taxonomies'] = array();
		$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );

		$post_type_info['get_object_taxonomies'] = $taxonomy_names;

		foreach ( $taxonomy_names as $taxonomy_name ) {
			$post_type_info['taxonomies'][] = array( 
				'key' => $taxonomy_name->name, 
				'value' => $taxonomy_name->label 
			);
		}

		if ( ! empty( $_POST["postTaxonomy"] ) ) {
			$taxonomy = strtolower( wp_filter_nohtml_kses( $_POST["postTaxonomy"] ) );
			$post_type_info['terms'] = array();
			$taxonomy_category = get_terms( $taxonomy );

			foreach ( $taxonomy_category as $taxonomy_cat ) {
				$post_type_info['terms'][] = array( 'key' => $taxonomy_cat->slug, 'value' => $taxonomy_cat->name );
			}
		}

		$templates = better_locate_available_plugin_templates( '/custom-post/' . $post_type . '/' );

		foreach ( $templates as $key => $value ) {
			// $image = better_locate_plugin_template_image( '/custom-post/' . $post_type . '/' . $key );

			// if ( !isset( $image ) ) {
			// 	$image = better_locate_plugin_template_image( '/custom-post/post/' . $key);
			// }

			$post_type_info['templates'][] = array( "key" => $key, "value" => $value );
		}

		$post_type_info['links'] = array(
			array( "key" => "none", "value" => __( "None", "better" ) ),
			array( "key" => "single", "value" => __( "Single Post", "better" ) )
		);

		// if ( $post_type != 'page' ) {
		// 	$post_type = Intense()->post_types->get_post_type( $post_type );
			
		// 	if ( is_null( $post_type ) ) {
		// 		$links_array = array();
		// 	} else {
		// 		$links_array = $post_type->get_link_fields();
		// 	}

		// 	$post_type_info['links'] = array_merge( $post_type_info['links'], $links_array );
		// }

		echo json_encode( $post_type_info );
	} 

	die();
}