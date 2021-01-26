<?php
/*
Better Template Name: Grid Masonry
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
		wp_get_attachment_image( get_post_thumbnail_id(), array( 480, 600 ) )
	);
}

// Wrap the text content
$output .= sprintf(
	'<div class="bb-block-post-overlay primary-background-color"></div><div class="bb-block-post-grid-text">'
);

	$output .= '<div class="bb-block-post-grid-text-inner"><div class="bb-block-post-overlay-info">';

	$post_meta = '';

	// Wrap the byline content
	$post_meta .= sprintf(
		'<div class="bb-post-meta"><div class="bb-block-post-grid-byline">'
	);

		// Get the post author
		if ( $display_post_categories && $display_post_categories !== '' ) {
			$post_meta .= sprintf(
				'<div class="bb-block-post-grid-category">%1$s</div>',
				get_the_term_list( get_the_ID(), $taxonomy, null, ', ', null )
			);
		}

		// Get the post date
		if ( $display_post_date && $display_post_date !== '' ) {
			$post_meta .= sprintf(
				'<time datetime="%1$s" class="bb-block-post-grid-date">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date( '' ) )
			);
		}

	// Close the byline content
	$post_meta .= sprintf(
		'</div></div>'
	);

	// overlay category
	$output .= sprintf(
		'<div class="post-overlay-meta">%1$s</div>',
		$post_meta
	);

	$output .= sprintf(
		'<h2 class="bb-block-post-grid-title"><a href="%1$s" rel="bookmark">%2$s</a></h2>',
		esc_url( get_permalink( $post_id ) ),
		esc_html( get_the_title() )
	);

	$output .= '</div></div>';

// Wrap the text content
$output .= sprintf(
	'</div>'
);

// Close the markup for the post
$output .= "</div>\n";

echo $output;