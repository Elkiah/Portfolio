<?php
/*
Better Template Name: Grid Classic w/ Caption
*/

// Start the markup for the post
$output .= sprintf(
	'<div class="%1$s">',
	$post_classes
);

// Get the featured image
if ( $display_post_image && has_post_thumbnail() ) {
	$output .= sprintf(
		'<div class="bb-block-post-grid-image"><a href="%1$s" rel="bookmark">%2$s</a></div>',
		esc_url( get_permalink() ),
		wp_get_attachment_image( get_post_thumbnail_id(), $image_size )
	);
}

// Wrap the text content
$output .= sprintf(
	'<div class="bb-block-post-overlay primary-background-color"></div><div class="bb-block-post-grid-text">'
);

	// if ( $post_type === 'summa_portfolio' ) {
	// 	$cats = get_the_term_list( get_the_ID(), 'summa_portfolio_category', null, ', ', null );
	// } else {
	// 	$cats = get_the_category_list( ', ' );
	// }

	// overlay category
	// if ( $postStyle === '2' ) {
	// 	$output .= sprintf(
	// 		'<div class="post-overlay-category">%1$s</div>',
	// 		$cats
	// 	);
	// }

	$output .= sprintf(
		'<h2 class="bb-block-post-grid-title"><a href="%1$s" rel="bookmark">%2$s</a></h2>',
		esc_url( get_permalink( $post_id ) ),
		esc_html( get_the_title() )
	);

	// Wrap the byline content
	$output .= sprintf(
		'<div class="bb-block-post-grid-byline">'
	);

		// Get the post author
		if ( $display_post_categories && $display_post_categories !== '' ) {
			$output .= sprintf(
				'<div class="bb-block-post-grid-category">%1$s</div>',
				get_the_term_list( get_the_ID(), $taxonomy, null, ', ', null )
			);
		}

		// Get the post date
		if ( $display_post_date && $display_post_date !== '' ) {
			$output .= sprintf(
				'<time datetime="%1$s" class="bb-block-post-grid-date">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date( '' ) )
			);
		}

	// Close the byline content
	$output .= sprintf(
		'</div>'
	);

	// Wrap the excerpt content
	$output .= sprintf(
		'<div class="bb-block-post-grid-excerpt">'
	);

		// Get the excerpt
		$excerpt = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_the_ID(), 'display' ) );

		if( empty( $excerpt ) ) {
			$excerpt = apply_filters( 'the_excerpt', wp_trim_words( get_the_content(), 35 ) );
		}

		if ( ! $excerpt ) {
			$excerpt = null;
		}

		if ( $display_post_excerpt && $display_post_excerpt !== '' ) {
			$output .=  wp_kses_post( $excerpt );
		}

		if ( $display_post_link && $read_more_text !== '' ) {
			$output .= sprintf(
				'<p><a class="bb-block-post-grid-link bb-text-link" href="%1$s" rel="bookmark">%2$s</a></p>',
				esc_url( get_permalink() ),
				esc_html( $read_more_text )
			);
		}

	// Close the excerpt content
	$output .= sprintf(
		'</div>'
	);

// Wrap the text content
$output .= sprintf(
	'</div>'
);

// Close the markup for the post
$output .= "</div>\n";

echo $output;