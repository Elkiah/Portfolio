<?php
/**
 * Gets a list of attachemnt ids for post featured images
 *
 * @return array list of attachemnt ids for post featured images
 */
function better_get_post_thumbnail_ids( $postid = null, $custom_post_field_key = null ) {
	global $post;

	if ( isset( $postid ) && get_post_status( $postid ) === FALSE ) {
		$thumbnail_post = $post;
	} else {
		if ( isset( $postid ) ) {
			$thumbnail_post = get_post( $postid );
		} else {
			$thumbnail_post = $post;
		}
	}

	$ids = array();

	//Custom Post Type image
	if ( isset( $custom_post_field_key ) ) {
		$image = get_field( $custom_post_field_key, $thumbnail_post->ID );

		if ( !empty( $image ) )	{
			$ids[] = $image['id'];
		}
	}

	$ids[] = get_post_thumbnail_id( $thumbnail_post->ID );
	$hasGallery = get_field('better_featured_gallery', $thumbnail_post->ID, false);
	$gallery = '';

	if ( $hasGallery ) {
		$gallery = array_filter( get_field('better_featured_gallery', $thumbnail_post->ID, false) );
	}

	if ( !empty( $gallery ) ) {
		$ids = array_merge($ids, $gallery);
	}

	$ids = array_filter( $ids );

	return array_values( $ids );
}
