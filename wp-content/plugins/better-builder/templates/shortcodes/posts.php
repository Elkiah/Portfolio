<?php
wp_enqueue_script( 'waypoints' );
wp_enqueue_script( 'isotope-packery' );
wp_enqueue_script( 'better.posts' );

$pagination_type = $pagination_output = $filter = $output = $post_item = $load_btn = $grid_class = '';

if ( $cpt_template === 'carousel-slider' ) {
	wp_enqueue_script( 'swiper' );
	wp_enqueue_style( 'swiper' );

	$grid_class .= 'swiper-wrapper';
	$slider_classes = 'bb-swiper pagination-style-5';
}

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$args = array(
	'post_type' => $post_type,
	'orderby' => $orderby,
	'order' => $order,
	'paged'=> $paged,
	'post_status' => 'publish',
	//'nopaging' => ( $show_all == 0 ? false : true ),
	//'tax_query' => '',
);

if ( $categories != '' && isset( $taxonomy ) && $taxonomy != '' ) {
	$iscat_array = true;
	$field = '';

	if ( strpos( $categories, ',' ) ) {
		$cat_array = explode( ',', str_replace( ' ', '-', strtolower( $categories ) ) );

		$field = array_filter( $cat_array, 'is_numeric' ) ? 'term_id' : 'slug';

		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'  => $field,
				'terms'  => $cat_array
			)
		);
	} else {
		$cat_array = array( $categories );

		$field = array_filter( $cat_array, 'is_numeric' ) ? 'term_id' : 'slug';

		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'  => $field,
				'terms'  => array( $categories )
			)
		);
	}
}

if ( $include_post_ids != '' ) {
	if ( strpos( $include_post_ids, ',' ) ) {
		$id_array = explode( ',', $include_post_ids );
		$args['post__in'] = $id_array;
	} else {
		$args['post__in'] = array( $include_post_ids );
	}
}

$args['category__not_in'] = array();

if ( $exclude_categories != '' ) {
	$ex_array = array();
	if ( strpos( $exclude_categories, ',' ) ) {
		$ex_array = explode( ',', $exclude_categories );
	} else {
		$ex_array = array( $exclude_categories );
	}

	if ( is_array( $ex_array ) ) {
		$ex_cat_array = array();

		$field = array_filter( $ex_array, 'is_numeric' ) ? 'term_taxonomy_id' : 'slug';

		foreach ( $ex_array as $ex_cat ) {
			$term = get_term_by( $field, $ex_cat, $taxonomy );

			if ( !empty( $term ) ) {
				$ex_cat_array[] = $term->term_id;

				if ( !empty( $taxonomy ) ) {
					$args['tax_query'] = array();
					$args['tax_query'][] = array(
						array(
							'taxonomy' => $taxonomy,
							'field'  => $field,
							'terms'  => $ex_cat,
							'operator'  => 'NOT IN'
						)
					);
				}
			}
		}

		if ( is_array( $ex_cat_array ) ) {
			$args['category__not_in'] = $ex_cat_array;
		}
	}
}

if ( $exclude_post_ids != '' ) {
	if ( strpos( $exclude_post_ids, ',' ) ) {
		$id_array = explode( ',', $exclude_post_ids );
		$args['post__not_in'] = $id_array;
	} else {
		$args['post__not_in'] = array( $exclude_post_ids );
	}
}

if ( $post_count != '' && is_numeric( $post_count ) ) {
	$args2 = $args;
	$post_in_array = array();
	$count = 0;
	$args2['nopaging'] = true;

	$theposts = get_posts( $args2 );

	$found_posts = count( $theposts );

	foreach( $theposts as $post ) {
		$post_in_array[] = $post->ID;
		$count++;

		if ( $count == $post_count ) {
			 break;
		}
	}

	wp_reset_postdata();

	if ( is_array( $post_in_array ) ) {
		$args['post__in'] = $post_in_array;
	}
}

$args['posts_per_page'] = $posts_per_page;

$post_query = new WP_Query( $args );

if ( $post_query->have_posts() ) {

	if ( $cpt_template === 'masonry-grid' ) {
		$post_item .= '<div class="grid-sizer"></div>';
	}

	while ( $post_query->have_posts() ) {
		$post_query->the_post();

		if ( has_post_thumbnail() && $display_post_image ) {
			$post_thumb_class = 'has-thumb';
		} else {
			$post_thumb_class = 'no-thumb';
		}
		
		$post_classes = array(
			'bb-post-item',
			$post_thumb_class
		);

		if ( $shadow ) {
			$post_classes[] = 'bb-shadow';
		}

		if ( $cpt_template !== 'grid-with-caption' ) {
			$post_classes[] = 'bb-overlay';
		}

		if ( $cpt_template === 'carousel-slider' ) {
			$post_classes[] = 'swiper-slide';
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$categories_list = array();

			foreach ( $terms as $term ) {
				$post_classes[] = $term->slug;
			}
		}

		$post_classes = get_post_class( implode( ' ', $post_classes ) );
		$atts['post_classes'] = implode( ' ', $post_classes );

		$post_item .= BetterCore()->template( 'custom-post/' . $post_type . '/' . $cpt_template, null, $atts, false );
	}
	
}

// Build the classes
$class .= " bb-block-post-grid align{$align}";

if ( isset( $className ) ) {
	$class .= ' ' . $className;
}

$grid_class .= ' bb-post-grid-items';

// if ( isset( $postLayout ) && 'list' === $postLayout ) {
// 	$grid_class .= ' is-list';
// } else {
// 	$grid_class .= ' is-grid';
// }

if ( ! stristr( $cpt_template, 'masonry' ) ) {
	$grid_class .= ' is-grid';
}

if ( isset( $columns ) ) {
	$grid_class .= ' columns-' . $columns;
}

if ( $cpt_template !== 'carousel-slider' && $css_animation !== '' ) {
	$grid_class .= ' has-animation';
}

if ( $css_animation !== '' ) {
	$grid_class .= ' ' . $css_animation;
}

// Filter option
$post_terms_args = array( 
	'taxonomy' => $taxonomy,
	'exclude' => $args['category__not_in'], 
	'hide_empty' => true 
);

if ( strpos( $categories, ',' ) ) {
	$terms_arr = array();
	$category_array = explode( ',', $categories );

	foreach( $category_array as $category ) {
		$term = get_term_by( 'slug', $category, $taxonomy );
		if ( ! empty( $term ) ) {
			$terms_arr[] = $term->term_id;
		}
	}

	$post_terms_args['include'] = $terms_arr;
}

$post_terms = get_terms( $post_terms_args );

$filter_output = '';
if ( $filter_all_text !== '' ) {
	$filter_output .= '<a href="javascript:void(0);" class="filter-btn active" data-filter="*">' . esc_html( $filter_all_text ) . '</a>';
}

foreach ( $post_terms as $term ) {
	$filter_output .= sprintf( 
		'<a href="javascript:void(0);" class="filter-btn" data-filter=".%1$s" data-ajax-filter="%3$s:%1$s">%2$s</a>',
		$term->slug,
		esc_html( $term->name ),
		$taxonomy
	);
}

$i     = 0;
$count = $post_query->post_count;

$better_query_params                   = $args;
$better_query_params['action']         = 'post_infinite_load';
$better_query_params['max_num_pages']  = $post_query->max_num_pages;
$better_query_params['found_posts']    = $post_query->found_posts;
$better_query_params['cpt_template']       = $cpt_template;
$better_query_params['pagination']     = $pagination;
$better_query_params['count']          = $count;
$better_query_params['taxonomy']       = $taxonomy;
$better_query_params['categories']     = $categories;
$better_query_params['atts']    	   = $atts;
$better_query_params                   = htmlspecialchars( wp_json_encode( $better_query_params ) );

$attributes = array(
	'data-filter-type' => $show_filter ? esc_attr( $filter_type ) : null,
);

if ( stristr( $cpt_template, 'carousel' ) ) {
	$attributes[ 'data-type' ] = 'swiper';
} elseif ( stristr( $cpt_template, 'masonry' ) ) {
	$attributes[ 'data-type' ] = 'masonry';
}

if ( $cpt_template !== 'carousel-slider' ) {
	$attr = array(
		'data-xs-columns' => 1,
		'data-sm-columns' => 2,
		'data-lg-columns' => $columns,
		'data-gutter' => $gutter,
	);
	$attributes = array_merge( $attributes, $attr );
}

if ( $pagination !== '' && $post_query->found_posts > $posts_per_page ) {
	$attributes[ 'data-pagination' ] = esc_attr( $pagination );
}

$swiper_attr = array(
	'data-xs-items' => 1,
	'data-sm-items' => 2,
	'data-md-items' => $columns,
	'data-lg-items' => $columns,
	'data-lg-gutter' => $gutter,
	'data-nav' => 1,
	'data-pagination' => 1,
	'data-autoplay' => 5000
);

?>
<style>
	@media (min-width: 1200px) {
		<?php echo '#' . $id; ?> .is-grid {
			<?php if ( $gutter != 0 ) {  ?>
				grid-column-gap: <?php echo $gutter; ?>px;
				grid-row-gap: <?php echo $gutter; ?>px;
			<?php } ?>
		}
	}
	<?php echo '#' . $id; ?> .is-grid {
		<?php if ( $gutter != 0 ) {  ?>
			grid-column-gap: <?php echo $gutter; ?>px;
			grid-row-gap: <?php echo $gutter; ?>px;
		<?php } ?>
	}
	/* cheat for gutenberg editor */
	.block-editor-page .bb-block-post-grid[data-type="masonry"] .bb-post-grid-items {
		column-gap: <?php echo $gutter; ?>px;
	}
	.block-editor-page .bb-block-post-grid[data-type="masonry"] .bb-post-item {
		margin-bottom: <?php echo $gutter; ?>px;
	}
</style>
<?php

// Output the post markup
$output .= '<div ' . $id_attr . ' class="' . esc_attr( $class ) . '" ' . BetterCore()->do_attributes( $attributes, false ) . '>';

	// filter output
	if ( $show_filter ) {
		$output .= sprintf(
			'<div class="bb-filter-button center"><div class="bb-filter-button-inner">%1$s</div></div>',
			$filter_output
		);
	}

	$output .= '<input type="hidden" class="bb-grid-query" value="' . $better_query_params . '"/>';

	if ( $cpt_template === 'carousel-slider' ) {
		$output .= '<div class="' . $slider_classes . '" ' . BetterCore()->do_attributes( $swiper_attr, false ) . '><div class="swiper-container">';
	}

		// post items
		$output .= '<div class="' . $grid_class . '">' . $post_item . '</div>';

		// pagination
		if ( $pagination !== '' && $post_query->found_posts > $posts_per_page ) {

			if ( $pagination === 'loadmore' ) {
				$load_btn = '<div class="inner"><a href="#" class="bb-grid-loadmore-btn heading-color"><span class="button-text">Load More</span><span class="button-icon primary-color fa fa-redo"></span></a></div>';
			}
		
			$output .= '<div class="bb-grid-pagination"><div class="pagination-wrapper" style="text-align:center"><div class="inner"><div class="bb-grid-loader">
			<svg width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#444"> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)" stroke-width="2"> <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle> <path d="M36 18c0-9.94-8.06-18-18-18" transform="rotate(48.1266 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform> </path> </g> </g> </svg>
			</div></div>'. $load_btn .'</div></div>';

		}

	if ( $cpt_template === 'carousel-slider' ) {
		$output .= '</div></div>';
	}

$output .= '</div>';

echo $output;