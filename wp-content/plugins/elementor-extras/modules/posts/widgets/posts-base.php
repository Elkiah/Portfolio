<?php
namespace ElementorExtras\Modules\Posts\Widgets;

// Extras for Elementor Classes
use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Modules\Posts\Module as PostsModule;

// Elementor Classes
use Elementor\Controls_Manager; 

// Elementor Pro Classes
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use ElementorPro\Modules\QueryControl\Module as Module_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Posts
 *
 * @since 1.6.0
 */
abstract class Posts_Base extends Extras_Widget {

	/**
	 * Query
	 *
	 * @since  2.2.0
	 * @var    \WP_Query
	 */
	protected $_query = null;

	/**
	 * Get Query
	 *
	 * @since  2.2.0
	 * @return object|\WP_Query
	 */
	public function get_query() {
		return $this->_query;
	}

	/**
	 * Register Query Content Controls
	 *
	 * @since  2.2.0
	 * @return void
	 */
	protected function register_query_content_controls( $condition = [] ) {
		
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => $condition,
			]
		);

			$this->add_group_control(
				Group_Control_Related::get_type(),
				[
					'name' => 'posts',
					'presets' => [ 'full' ],
					'exclude' => [
						'posts_per_page', //use the one from Layout section
						'ignore_sticky_posts'
					],
				]
			);

		$this->end_controls_section();

				$this->start_injection( [
			'at' => 'after',
			'of' => 'posts_select_date',
		] );

			$this->update_control( 'posts_orderby', [
				'options' => [
					'post_date' 		=> __( 'Date', 'elementor-extras' ),
					'post_title' 		=> __( 'Title', 'elementor-extras' ),
					'menu_order' 		=> __( 'Menu Order', 'elementor-extras' ),
					'rand' 				=> __( 'Random', 'elementor-extras' ),
					'meta_value'		=> __( 'Meta Value (text)', 'elementor-extras' ),
					'meta_value_num'	=> __( 'Meta Value (number)', 'elementor-extras' )
				],
			] );

		$this->end_injection();

		$this->start_injection( [
			'at' => 'after',
			'of' => 'posts_orderby',
		] );

			$this->add_control( 'posts_orderby_meta_key',
				[
					'label' 		=> __( 'Meta Key', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> '',
					'condition' => [
						'posts_orderby' => [ 'meta_value', 'meta_value_num' ],
					],
				]
			);

		$this->end_injection();

		$this->start_injection( [
			'at' => 'after',
			'of' => 'posts_order',
		] );

			$this->add_control(
				'sticky_posts',
				[
					'label' 		=> __( 'Sticky Posts', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'separator'		=> 'before',
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'sticky_posts_info',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'Preview of sticky posts option is only available on frontend.', 'elementor-extras' ),
					'content_classes' 	=> 'elementor-panel-alert elementor-panel-alert-info',
					'condition' 		=> [
						'sticky_posts!' => '',
						'sticky_only' => '',
					],
				]
			);

			$this->add_control(
				'sticky_only',
				[
					'label' 		=> __( 'Show Only Sticky Posts', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'condition' 	=> [
						'sticky_posts!' => '',
						'posts_post_type!' => 'by_id',
					],
					'return_value' 	=> 'yes',
				]
			);

		$this->end_injection();
	}

	/**
	 * Query Posts
	 *
	 * @since  2.2.0
	 * @return void
	 */
	public function query_posts() {

		$query_args = [
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' 		=> $this->get_posts_per_page(),
			'paged' 				=> $this->get_current_page(),
		];

		if ( $this->get_settings( 'posts_orderby_meta_key' ) ) {
			$query_args['meta_key'] = $this->get_settings( 'posts_orderby_meta_key' );
		}

		if ( 'yes' === $this->get_settings('sticky_posts') ) {
			$sticky_posts = get_option('sticky_posts');

			$post__in = ! empty( $query_args['post__in'] ) ? $query_args['post__in'] : [];

			if ( 'yes' === $this->get_settings('sticky_only') ) {
				if ( empty( $sticky_posts ) ) {
					$sticky_posts = [0];
				}

				$query_args['post__in'] = array_merge( $post__in, $sticky_posts );
			} else {
				$query_args['ignore_sticky_posts'] = 0;
			}
		}

		$this->set_query( $query_args );
	}

	/**
	 * Retrieve posts per page setting
	 *
	 * @since 2.2.16
	 */
	public function get_posts_per_page() {
		$posts_per_page = $this->get_settings('posts_per_page');

		if ( 'current_query' === $this->get_settings('posts_post_type') || ! $posts_per_page ) {
			$posts_per_page = (int)get_option( 'posts_per_page' );
		} else if ( 0 >= $posts_per_page ) {
			$posts_per_page = -1;
		}

		return $posts_per_page;
	}

	/**
	 * Checks for the Query ID and inits the WP_Query object
	 *
	 * @since 2.2.0
	 * @param Array $query_args
	 */
	public function set_query( $query_args ) {

		if ( ! is_elementor_pro_active() ) {
			return;
		}

		if ( '' === $query_args['posts_per_page'] ) {
			// Handle empty posts per page setting
			$query_args['posts_per_page'] = (int)get_option( 'posts_per_page' );
		}
		
		$elementor_query = Module_Query::instance();
		
		add_filter( 'elementor/query/get_query_args/current_query', [ $this, 'fix_default_query_args' ] );

		$this->_query = $elementor_query->get_query( $this, 'posts', $query_args, [] );

		/**
		 * Query Filter
		 *
		 * Filters the current query
		 *
		 * @since 2.1.3
		 * @param WP_Query 			$query 		The initial query
		 */
		$this->_query = apply_filters( 'elementor_extras/widgets/posts/query', $this->_query );

		remove_filter( 'elementor/query/get_query_args/current_query', [ $this, 'fix_default_query_args' ] );
	}

	/**
	 * Filter to override posts per page on current query setting
	 *
	 * @since 2.2.0
	 * @param Array $global_args
	 */
	public function fix_default_query_args( $global_args ) {

		// When using current_query some default categories are set with a new WP_Query
		// which restrict results in archive pages
		if ( 'current_query' === $this->get_settings( 'posts_post_type' ) ) {
			if ( ! is_category() ) {
				$global_args['cat'] = false;
				$global_args['category_name'] = '';
			}
		}

		// Fix posts per page
		// if ( $posts_per_page && $posts_per_page > 0 ) {
		// 	$global_args['posts_per_page'] = $posts_per_page;
		// }

		return $global_args;
	}

	/**
	 * Get Formatted Date
	 *
	 * Format a date based on format settings
	 *
	 * @since 2.2.0
	 * @param string $custom 		Wether the format is custom or not
	 * @param string $date_format 	The date format
	 * @param string $time_format 	The time format
	 */
	public function get_date_formatted( $custom = false, $custom_format, $date_format, $time_format, $post_id = null ) {
		if ( $custom ) {
			$format = $custom_format;
		} else {
			$date_format = $date_format;
			$time_format = $time_format;
			$format = '';

			if ( 'default' === $date_format ) {
				$date_format = get_option( 'date_format' );
			}

			if ( 'default' === $time_format ) {
				$time_format = get_option( 'time_format' );
			}

			if ( $date_format ) {
				$format = $date_format;
				$has_date = true;
			} else {
				$has_date = false;
			}

			if ( $time_format ) {
				if ( $has_date ) {
					$format .= ' ';
				}
				$format .= $time_format;
			}
		}

		$value = get_the_date( $format, $post_id );
		
		return wp_kses_post( $value );
	}
}
