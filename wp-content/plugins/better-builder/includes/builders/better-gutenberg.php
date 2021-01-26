<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class BetterB_Gutenberg extends Better_Builder {

	function __construct() {

		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );

        if ( ! is_admin() ) {
			add_action( 'enqueue_block_assets', array( $this, 'enqueue_frontend_assets' ) );
		}

		add_action( 'init', array( $this, 'blocks_init_post_meta' ) );

		add_action( 'admin_head-post.php', array( $this, 'blocks_admin_editor_width' ), 110 );
		add_action( 'admin_head-post-new.php', array( $this, 'blocks_admin_editor_width' ), 110 );

		add_filter( 'admin_body_class', array( $this, 'blocks_admin_body_class' ) );

		add_action( 'init', array( $this, 'server_side' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50, 1 );

		add_action( 'wp_head', array( $this, 'frontend_inline_css' ), 90 );

		// Filter to create Better Builder block category
		add_filter( 'block_categories', function ( $categories, $post ) {
			return array_merge(
				$categories,
				array(
					array(
						'slug' => 'better-builder',
						'title' => esc_html__( 'Better Builder', 'better' )
					),
				)
			);
		}, 10, 2 );

		// rest api fields
		add_action( 'rest_api_init', array( $this, 'blocks_register_rest_fields' ) );
	}

	/**
	 * Register Meta for blocks width
	 */
	public function blocks_init_post_meta() {
		register_post_meta( '', 'bb_blocks_editor_width', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		) );
	}

	/**
	 * Add class to match editor width.
	 */
	public function blocks_admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( 'post' == $screen->base ) {
			global $post;
			$editorwidth = get_post_meta( $post->ID, 'bb_blocks_editor_width', true );
			if ( isset( $editorwidth ) && ! empty( $editorwidth ) && 'default' !== $editorwidth ) {
				$classes .= ' bb-editor-width-' . esc_attr( $editorwidth );
			} else {
				$classes .= ' bb-editor-width-default';
			}
		}
		return $classes;
	}

	/**
	 * Create API fields for additional info
	 */
	public function blocks_register_rest_fields() {
		// Add landscape featured image source
		register_rest_field ( array( 'summa_portfolio', 'post' ),
			'better-post-grid-480x480',
			array(
				'get_callback' 		=> array( $this, 'blocks_get_image_src_480_480' ),
				'update_callback' 	=> null,
				'schema' 			=> null,
			)
		);

		// Add square featured image source
		register_rest_field( array( 'summa_portfolio', 'post' ),
			'better-post-grid-370x250',
			array(
				'get_callback' => array( $this, 'blocks_get_image_src_370_250' ),
				'update_callback' => null,
				'schema' => null,
			)
		);
		
		// Add 370x560 featured image source
		register_rest_field( array( 'summa_portfolio', 'post' ),
			'better-post-grid-370x560',
			array(
				'get_callback' => array( $this, 'blocks_get_image_src_370_560' ),
				'update_callback' => null,
				'schema' => null,
			)
		);

		// Add author info
		register_rest_field( array( 'summa_portfolio', 'post' ),
			'author_info',
			array(
				'get_callback' => array( $this, 'blocks_get_author_info' ),
				'update_callback' => null,
				'schema' => null,
			)
		);

		// Add category data
		register_rest_field ( array( 'summa_portfolio', 'post' ),
			'cats',
			array(
				'get_callback' 		=> array( $this, 'blocks_get_category_data' ),
				'update_callback' 	=> null,
				'schema' 			=> null,
			)
		);
	}

	public function blocks_get_category_data( $object, $field_name, $request ) {
		if ( $object['type'] === 'summa_portfolio' ) {
			$cats = get_the_terms( $object['id'], 'summa_portfolio_category' );
		} else {
			$cats = get_the_category( $object['id'] );
		}

		return $cats;
	}

	/**
	 * Get landscape featured image source for the rest field
	 */
	public function blocks_get_image_src_480_480( $object, $field_name, $request ) {
		if ( $object[ 'featured_media' ] ) {
			$feat_img_array = wp_get_attachment_image_src(
				$object['featured_media'],
				'better-post-grid-480x480',
				false
			);
			return $feat_img_array[0];
		}
		return false;
	}

	/**
	 * Get 370x250 featured image source for the rest field
	 */
	public function blocks_get_image_src_370_250( $object, $field_name, $request ) {
		if ( $object[ 'featured_media' ] ) {
			$feat_img_array = wp_get_attachment_image_src(
				$object['featured_media'],
				'better-post-grid-370x250',
				false
			);
			return $feat_img_array[0];
		}
		return false;
	}

	/**
	 * Get 370x250 featured image source for the rest field
	 */
	public function blocks_get_image_src_370_560( $object, $field_name, $request ) {
		if ( $object[ 'featured_media' ] ) {
			$feat_img_array = wp_get_attachment_image_src(
				$object['featured_media'],
				'better-post-grid-370x560',
				false
			);
			return $feat_img_array[0];
		}
		return false;
	}

	/**
	 * Get author info for the rest field
	 */
	public function blocks_get_author_info( $object, $field_name, $request ) {
		// Get the author name
		$author_data['display_name'] = get_the_author_meta( 'display_name', $object['author'] );

		// Get the author link
		$author_data['author_link'] = get_author_posts_url( $object['author'] );

		// Return the author data
		return $author_data;
	}

	/**
	 * Footer Styling
	 *
	 * @access public
	 */
	public function dimensions_styles() {
		global $post;

		if ( $post && isset( $post->ID ) ) {

			$meta    = get_post_meta( $post->ID, '_betterbuilder_dimensions', true );
			$desktop = array();
			$tablet  = array();
			$mobile  = array();

			if ( $meta ) {

				$meta = json_decode( $meta );

				if ( ! empty( $meta ) ) {

					$output = '';
					foreach ( $meta as $id => $block ) {

						$output .= sprintf( '.%1$s {', esc_attr( $id ) );

						if ( ! empty( $block ) ) {
							foreach ( $block as $key => $style ) {
								if ( ! empty( $style ) ) {
									foreach ( $style as $ky => $value ) {
										if( !empty( $value ) ){
											if (  strpos( $ky, 'Mobile' ) !== false ) {
												$mobile[] = strtolower( preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1-', str_replace( 'Mobile', '', $ky ) ) ) . ':' . esc_attr( $value ) . ';';
											} elseif ( strpos( $ky, 'Tablet' ) !== false ) {
												$tablet[] = strtolower( preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1-', str_replace( 'Tablet', '', $ky ) ) ) . ':' . esc_attr( $value ) . ';';
											} else {
												$output .= strtolower( preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1-', $ky ) ) . ':' . esc_attr( $value ) . ';';
											}
										}
									}
								}
							}
						}

						$output .= '}';

						if ( ! empty( $tablet ) ) {
							$output .= '@media (max-width: ' . apply_filters( 'betterbuilder_tablet_breakpoint', '768px' ) . ') {';
							$output .= sprintf( '.%1$s {', esc_attr( $id ) );
							foreach ( $tablet as $tablet_setting ) {
								$output .= $tablet_setting;
							}
							$output .= '}';
							$output .= '}';
						}

						if ( ! empty( $mobile ) ) {
							$output .= '@media (max-width: ' . apply_filters( 'betterbuilder_desktop_breakpoint', '514px' ) . ') {';
							$output .= sprintf( '.%1$s {', esc_attr( $id ) );
							foreach ( $mobile as $mobile_setting ) {
								$output .= $mobile_setting;
							}
							$output .= '}';
							$output .= '}';
						}

						// Reset media queries.
						$tablet = array();
						$mobile = array();
					}

					return wp_strip_all_tags( $output );
				}
			}
		}
	}

	/**
	 * Gets the parsed blocks, need to use this becuase wordpress 5 doesn't seem to include gutenberg_parse_blocks
	 */
	public function better_gutenberg_parse_blocks( $content ) {
		$parser_class = apply_filters( 'block_parser_class', 'WP_Block_Parser' );
		if ( class_exists( $parser_class ) ) {
			$parser = new $parser_class();
			return $parser->parse( $content );
		} elseif ( function_exists( 'gutenberg_parse_blocks' ) ) {
			return gutenberg_parse_blocks( $content );
		} else {
			return false;
		}
	}
	/**
	 * Outputs extra css for blocks.
	 */
	public function frontend_inline_css() {
		if ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) ) {
			global $post;
			if ( ! is_object( $post ) ) {
				return;
			}
			$blocks = $this->better_gutenberg_parse_blocks( $post->post_content );
			//print_r($blocks );
			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				return;
			}
			$css  = '<style type="text/css" media="all" id="better-gutenberg-blocks-frontend">';
			foreach ( $blocks as $indexkey => $block ) {
				if ( ! is_object( $block ) && is_array( $block ) && isset( $block['blockName'] ) ) {
					if ( 'bb-gutenberg/rowlayout' === $block['blockName'] ) {
						if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
							$blockattr = $block['attrs'];
							if ( isset( $blockattr['uniqueID'] ) ) {
								// Create CSS for Row/Layout.
								$unique_id = $blockattr['uniqueID'];
								$css .= $this->row_layout_array_css( $blockattr, $unique_id );
								if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
									$css .= $this->column_layout_cycle( $block['innerBlocks'], $unique_id );
								}
							}
						}
					}
					if ( 'core/block' === $block['blockName'] ) {
						if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
							$blockattr = $block['attrs'];
							if ( isset( $blockattr['ref']  ) ) {
								$reusable_block = get_post( $blockattr['ref'] );
								if ( $reusable_block && 'wp_block' == $reusable_block->post_type ) {
									$reuse_data_block = $this->better_gutenberg_parse_blocks( $reusable_block->post_content );
									$css .= $this->blocks_cycle_through( $reuse_data_block );
								}
							}
						}
					}
					if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
						$css .= $this->blocks_cycle_through( $block['innerBlocks'] );
					}
				}
				if ( is_object( $block ) && isset( $block->blockName ) ) {
					if ( 'bb-gutenberg/rowlayout' === $block->blockName ) {
						if ( isset( $block->attrs ) && is_object( $block->attrs ) ) {
							$blockattr = $block->attrs;
							if ( isset( $blockattr->uniqueID ) ) {
								// Create CSS for Row/Layout.
								$unique_id = $blockattr->uniqueID;
								$css .= $this->row_layout_css( $blockattr, $unique_id );
								if ( isset( $block->innerBlocks ) && ! empty( $block->innerBlocks ) && is_array( $block->innerBlocks ) ) {
									$css .= $this->column_layout_cycle( $block->innerBlocks , $unique_id );
								}
							}
						}
					}
					if ( isset( $block->innerBlocks ) && ! empty( $block->innerBlocks ) && is_array( $block->innerBlocks ) ) {
						$css .= $this->blocks_cycle_through( $block->innerBlocks );
					}
				}
			}
			$css .= '</style>';
			echo $css;
		}
	}

	/**
	 * Builds css for inner columns
	 * 
	 * @param object/array $inner_blocks array of inner blocks.
	 */
	public function column_layout_cycle( $inner_blocks, $unique_id ) {
		$css = '';
		foreach ( $inner_blocks as $in_indexkey => $inner_block ) {
			if ( is_object( $inner_block ) ) {
				if ( isset( $inner_block->blockName ) ) {
					if ( 'bb-gutenberg/column' === $inner_block->blockName ) {
						if ( isset( $inner_block->attrs ) && is_object( $inner_block->attrs ) ) {
							$blockattr = $inner_block->attrs;
							$csskey = $in_indexkey + 1;
							$css .= $this->column_layout_css( $blockattr, $unique_id, $csskey );
						} elseif ( isset( $inner_block->attrs ) && is_array( $inner_block->attrs ) ) {
							$blockattr = $inner_block->attrs;
							$csskey = $in_indexkey + 1;
							$css .= $this->column_layout_array_css( $blockattr, $unique_id, $csskey );
						}
					}
				}
			} elseif ( is_array( $inner_block ) ) {
				if ( isset( $inner_block['blockName'] ) ) {
					if ( 'bb-gutenberg/column' === $inner_block['blockName'] ) {
						if ( isset( $inner_block['attrs'] ) && is_array( $inner_block['attrs'] ) ) {
							$blockattr = $inner_block['attrs'];
							$csskey = $in_indexkey + 1;
							$css .= $this->column_layout_array_css( $blockattr, $unique_id, $csskey );
						}
					}
				}	
			}
		}
		return $css;
	}

	/**
	 * Builds css for inner blocks
	 * 
	 * @param array $inner_blocks array of inner blocks.
	 */
	function blocks_cycle_through( $inner_blocks ) {
		$css = '';
		foreach ( $inner_blocks as $in_indexkey => $inner_block ) {
			if ( ! is_object( $inner_block ) && is_array( $inner_block ) && isset( $inner_block['blockName'] ) ) {
				if ( 'bb-gutenberg/rowlayout' === $inner_block['blockName'] ) {
					if ( isset( $inner_block['attrs'] ) && is_array( $inner_block['attrs'] ) ) {
						$blockattr = $inner_block['attrs'];
						if ( isset( $blockattr['uniqueID'] ) ) {
							// Create CSS for Row/Layout.
							$unique_id = $blockattr['uniqueID'];
							$css .= $this->row_layout_array_css( $blockattr, $unique_id );
							if ( isset( $inner_block['innerBlocks'] ) && ! empty( $inner_block['innerBlocks'] ) && is_array( $inner_block['innerBlocks'] ) ) {
								$css .= $this->column_layout_cycle( $inner_block['innerBlocks'], $unique_id );
							}
						}
					}
				}
				if ( 'core/block' === $inner_block['blockName'] ) {
					if ( isset( $inner_block['attrs'] ) && is_array( $inner_block['attrs'] ) ) {
						$blockattr = $inner_block['attrs'];
						if ( isset( $blockattr['ref']  ) ) {
							$reusable_block = get_post( $blockattr['ref'] );
							if ( $reusable_block && 'wp_block' == $reusable_block->post_type ) {
								$reuse_data_block = $this->better_gutenberg_parse_blocks( $reusable_block->post_content );
								$css .= $this->blocks_cycle_through( $reuse_data_block );
							}
						}
					}
				}
				if ( isset( $inner_block['innerBlocks'] ) && ! empty( $inner_block['innerBlocks'] ) && is_array( $inner_block['innerBlocks'] ) ) {
					$css .= $this->blocks_cycle_through( $inner_block['innerBlocks'] );
				}
			} elseif ( is_object( $inner_block ) && isset( $inner_block->blockName ) ) {
				if ( 'bb-gutenberg/rowlayout' === $inner_block->blockName ) {
					if ( isset( $inner_block->attrs ) && is_object( $inner_block->attrs ) ) {
						$blockattr = $inner_block->attrs;
						if ( isset( $blockattr->uniqueID ) ) {
							// Create CSS for Row/Layout.
							$unique_id = $blockattr->uniqueID;
							$css .= $this->row_layout_css( $blockattr, $unique_id );
							if ( isset( $inner_block->innerBlocks ) && ! empty( $inner_block->innerBlocks ) && is_array( $inner_block->innerBlocks ) ) {
								$css .= $this->column_layout_cycle( $inner_block->innerBlocks , $unique_id );
							}
						}
					} elseif ( isset( $inner_block->attrs ) && is_array( $inner_block->attrs ) ) {
						$blockattr = $inner_block->attrs;
						if ( isset( $blockattr['uniqueID'] ) ) {
							// Create CSS for Row/Layout.
							$unique_id = $blockattr['uniqueID'];
							$css .= $this->row_layout_array_css( $blockattr, $unique_id );
							if ( isset( $inner_block->innerBlocks ) && ! empty( $inner_block->innerBlocks ) && is_array( $inner_block->innerBlocks ) ) {
								$css .= $this->column_layout_cycle( $inner_block->innerBlocks , $unique_id );
							}
						}
					}
				}
				if ( isset( $inner_block->innerBlocks ) && ! empty( $inner_block->innerBlocks ) && is_array( $inner_block->innerBlocks ) ) {
					$css .= $this->blocks_cycle_through( $inner_block->innerBlocks );
				}
			}
		}
		return $css;
	}
	/**
	 * Builds Css for row layout block.
	 * 
	 * @param array  $attr the blocks attr.
	 * @param string $unique_id the blocks attr ID.
	 */
	function row_layout_css( $attr, $unique_id ) {
		$css = '';
		if ( isset( $attr->bgColor ) || isset( $attr->bgImg ) || isset( $attr->topMargin ) || isset( $attr->bottomMargin ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' {';
			if ( isset( $attr->topMargin ) ) {
				$css .= 'margin-top:' . $attr->topMargin . 'px;';
			}
			if ( isset( $attr->bottomMargin ) ) {
				$css .= 'margin-bottom:' . $attr->bottomMargin . 'px;';
			}
			if ( isset( $attr->bgColor ) ) {
				$css .= 'background-color:' . $attr->bgColor . ';';
			}
			if ( isset( $attr->bgImg ) ) {
				$css .= 'background-image:url(' . $attr->bgImg . ');';
				$css .= 'background-size:' . ( isset( $attr->bgImgSize ) ? $attr->bgImgSize : 'cover' ) . ';';
				$css .= 'background-position:' . ( isset( $attr->bgImgPosition ) ? $attr->bgImgPosition : 'center center' ) . ';';
				$css .= 'background-attachment:' . ( isset( $attr->bgImgAttachment ) ? $attr->bgImgAttachment : 'scroll' ) . ';';
				$css .= 'background-repeat:' . ( isset( $attr->bgImgRepeat ) ? $attr->bgImgRepeat : 'no-repeat' ) . ';';
			}
			$css .= '}';
		}
		if ( isset( $attr->bottomSep ) && 'none' != $attr->bottomSep ) {
			if ( isset( $attr->bottomSepHeight ) || isset( $attr->bottomSepWidth ) ) {
				if ( isset( $attr->bottomSepHeight ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
						$css .= 'height:' . $attr->bottomSepHeight . 'px;';
					$css .= '}';
				}
				if ( isset( $attr->bottomSepWidth ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
						$css .= 'width:' . $attr->bottomSepWidth . '%;';
					$css .= '}';
				}
			}
			if ( isset( $attr->bottomSepHeightTablet ) || isset( $attr->bottomSepWidthTablet ) ) {
				$css .= '@media (min-width: 767px) and (max-width: 1024px) {';
					if ( isset( $attr->bottomSepHeightTablet ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr->bottomSepHeightTablet . 'px;';
						$css .= '}';
					}
					if ( isset( $attr->bottomSepWidthTablet ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr->bottomSepWidthTablet . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
			if ( isset( $attr->bottomSepHeightMobile ) || isset( $attr->bottomSepWidthMobile ) ) {
				$css .= '@media (max-width: 767px) {';
					if ( isset( $attr->bottomSepHeightMobile ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr->bottomSepHeightMobile . 'px;';
						$css .= '}';
					}
					if ( isset( $attr->bottomSepWidthMobile ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr->bottomSepWidthMobile . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
		}
		if ( isset( $attr->topSep ) && 'none' != $attr->topSep ) {
			if ( isset( $attr->topSepHeight ) || isset( $attr->topSepWidth ) ) {
				if ( isset( $attr->topSepHeight ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
						$css .= 'height:' . $attr->topSepHeight . 'px;';
					$css .= '}';
				}
				if ( isset( $attr->topSepWidth ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
						$css .= 'width:' . $attr->topSepWidth . '%;';
					$css .= '}';
				}
			}
			if ( isset( $attr->topSepHeightTablet ) || isset( $attr->topSepWidthTablet ) ) {
				$css .= '@media (min-width: 767px) and (max-width: 1024px) {';
					if ( isset( $attr->topSepHeightTablet ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr->topSepHeightTablet . 'px;';
						$css .= '}';
					}
					if ( isset( $attr->topSepWidthTablet ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr->topSepWidthTablet . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
			if ( isset( $attr->topSepHeightMobile ) || isset( $attr->topSepWidthMobile ) ) {
				$css .= '@media (max-width: 767px) {';
					if ( isset( $attr->topSepHeightMobile ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr->topSepHeightMobile . 'px;';
						$css .= '}';
					}
					if ( isset( $attr->topSepWidthMobile ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr->topSepWidthMobile . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
		}
		if ( isset( $attr->topPadding ) || isset( $attr->bottomPadding ) || isset( $attr->leftPadding ) || isset( $attr->rightPadding ) || isset( $attr->minHeight ) ||  isset( $attr->maxWidth ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap {';
				if ( isset( $attr->topPadding ) ) {
					$css .= 'padding-top:' . $attr->topPadding . 'px;';
				}
				if ( isset( $attr->bottomPadding ) ) {
					$css .= 'padding-bottom:' . $attr->bottomPadding . 'px;';
				}
				if ( isset( $attr->leftPadding ) ) {
					$css .= 'padding-left:' . $attr->leftPadding . 'px;';
				}
				if ( isset( $attr->rightPadding ) ) {
					$css .= 'padding-right:' . $attr->rightPadding . 'px;';
				}
				if ( isset( $attr->minHeight ) ) {
					$css .= 'min-height:' . $attr->minHeight . 'px;';
				}
				if ( isset( $attr->maxWidth ) ) {
					$css .= 'max-width:' . $attr->maxWidth . 'px;';
					$css .= 'margin-left:auto;';
					$css .= 'margin-right:auto;';
				}
			$css .= '}';
		}
		if ( isset( $attr->overlay ) || isset( $attr->overlayBgImg ) || isset( $attr->overlaySecond ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-layout-overlay {';
				if ( isset( $attr->overlayOpacity ) ) {
					if ( $attr->overlayOpacity < 10 ) {
						$css .= 'opacity:0.0' . $attr->overlayOpacity . ';';
					} else if ( $attr->overlayOpacity >= 100 ) {
						$css .= 'opacity:1;';
					} else {
						$css .= 'opacity:0.' . $attr->overlayOpacity . ';';
					}
				}
				if ( isset( $attr->currentOverlayTab ) && 'grad' == $attr->currentOverlayTab ) {
					$type = ( isset( $attr->overlayGradType ) ? $attr->overlayGradType : 'linear');
					if ( 'radial' === $type ) {
						$angle = ( isset( $attr->overlayBgImgPosition ) ? 'at ' . $attr->overlayBgImgPosition : 'at center center' );
					} else {
						$angle = ( isset( $attr->overlayGradAngle ) ? $attr->overlayGradAngle . 'deg' : '180deg');
					}
					$loc = ( isset( $attr->overlayGradLoc ) ? $attr->overlayGradLoc : '0');
					$color = ( isset( $attr->overlay ) ? $attr->overlay : 'transparent');
					$locsecond = ( isset( $attr->overlayGradLocSecond ) ? $attr->overlayGradLocSecond : '100');
					$colorsecond = ( isset( $attr->overlaySecond ) ? $attr->overlaySecond : '#00B5E2');
					$css .= 'background-image: ' . $type . '-gradient(' . $angle. ', ' . $color . ' ' . $loc . '%, ' . $colorsecond . ' ' . $locsecond . '%);';
				} else {
					if ( isset( $attr->overlay ) ) {
						$css .= 'background-color:' . $attr->overlay . ';';
					}
					if ( isset( $attr->overlayBgImg ) ) {
						$css .= 'background-image:url(' . $attr->overlayBgImg . ');';
						$css .= 'background-size:' . ( isset( $attr->overlayBgImgSize ) ? $attr->overlayBgImgSize : 'cover' ) . ';';
						$css .= 'background-position:' . ( isset( $attr->overlayBgImgPosition ) ? $attr->overlayBgImgPosition : 'center center' ) . ';';
						$css .= 'background-attachment:' . ( isset( $attr->overlayBgImgAttachment ) ? $attr->overlayBgImgAttachment : 'scroll' ) . ';';
						$css .= 'background-repeat:' . ( isset( $attr->overlayBgImgRepeat ) ? $attr->overlayBgImgRepeat : 'no-repeat' ) . ';';
					}
				}
				if ( isset( $attr->overlayBlendMode ) ) {
					$css .= 'mix-blend-mode:' . $attr->overlayBlendMode . ';';
				}
			$css .= '}';
		}
		if ( isset( $attr->topPaddingM ) || isset( $attr->bottomPaddingM ) || isset( $attr->leftPaddingM ) || isset( $attr->rightPaddingM ) || isset( $attr->topMarginM ) || isset( $attr->bottomMarginM ) ) {
			$css .= '@media (max-width: 767px) {';
				if ( isset( $attr->topMarginM ) || isset( $attr->bottomMarginM ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' {';
						if ( isset( $attr->topMarginM ) ) {
							$css .= 'margin-top:' . $attr->topMarginM . 'px;';
						}
						if ( isset( $attr->bottomMarginM ) ) {
							$css .= 'margin-bottom:' . $attr->bottomMarginM . 'px;';
						}
					$css .= '}';
				}
				if ( isset( $attr->topPaddingM ) || isset( $attr->bottomPaddingM ) || isset( $attr->leftPaddingM ) || isset( $attr->rightPaddingM ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap {';
					if ( isset( $attr->topPaddingM ) ) {
						$css .= 'padding-top:' . $attr->topPaddingM . 'px;';
					}
					if ( isset( $attr->bottomPaddingM ) ) {
						$css .= 'padding-bottom:' . $attr->bottomPaddingM . 'px;';
					}
					if ( isset( $attr->leftPaddingM ) ) {
						$css .= 'padding-left:' . $attr->leftPaddingM . 'px;';
					}
					if ( isset( $attr->rightPaddingM ) ) {
						$css .= 'padding-right:' . $attr->rightPaddingM . 'px;';
					}
					$css .= '}';
				}
			
			$css .= '}';
		}
		return $css;
	}
	/**
	 * Builds Css for row layout block.
	 * 
	 * @param array  $attr the blocks attr.
	 * @param string $unique_id the blocks attr ID.
	 */
	function row_layout_array_css( $attr, $unique_id ) {
		$css = '';
		if ( isset( $attr['bgColor'] ) || isset( $attr['bgImg'] ) || isset( $attr['topMargin'] ) || isset( $attr['bottomMargin'] ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' {';
			if ( isset( $attr['topMargin'] ) ) {
				$css .= 'margin-top:' . $attr['topMargin'] . 'px;';
			}
			if ( isset( $attr['bottomMargin'] ) ) {
				$css .= 'margin-bottom:' . $attr['bottomMargin'] . 'px;';
			}
			if ( isset( $attr['bgColor'] ) ) {
				$css .= 'background-color:' . $attr['bgColor'] . ';';
			}
			if ( isset( $attr['bgImg'] ) ) {
				$css .= 'background-image:url(' . $attr['bgImg'] . ');';
				$css .= 'background-size:' . ( isset( $attr['bgImgSize'] ) ? $attr['bgImgSize'] : 'cover' ) . ';';
				$css .= 'background-position:' . ( isset( $attr['bgImgPosition'] ) ? $attr['bgImgPosition'] : 'center center' ) . ';';
				$css .= 'background-attachment:' . ( isset( $attr['bgImgAttachment'] ) ? $attr['bgImgAttachment'] : 'scroll' ) . ';';
				$css .= 'background-repeat:' . ( isset( $attr['bgImgRepeat'] ) ? $attr['bgImgRepeat'] : 'no-repeat' ) . ';';
			}
			$css .= '}';
		}
		if ( isset( $attr['bottomSep'] ) && 'none' != $attr['bottomSep'] ) {
			if ( isset( $attr['bottomSepHeight'] ) || isset( $attr['bottomSepWidth'] ) ) {
				if ( isset( $attr['bottomSepHeight'] ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
						$css .= 'height:' . $attr['bottomSepHeight'] . 'px;';
					$css .= '}';
				}
				if ( isset( $attr['bottomSepWidth'] ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
						$css .= 'width:' . $attr['bottomSepWidth'] . '%;';
					$css .= '}';
				}
			}
			if ( isset( $attr['bottomSepHeightTablet'] ) || isset( $attr['bottomSepWidthTablet'] ) ) {
				$css .= '@media (min-width: 767px) and (max-width: 1024px) {';
					if ( isset( $attr['bottomSepHeightTablet'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr['bottomSepHeightTablet'] . 'px;';
						$css .= '}';
					}
					if ( isset( $attr['bottomSepWidthTablet'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr['bottomSepWidthTablet'] . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
			if ( isset( $attr['bottomSepHeightMobile'] ) || isset( $attr['bottomSepWidthMobile'] ) ) {
				$css .= '@media (max-width: 767px) {';
					if ( isset( $attr['bottomSepHeightMobile'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr['bottomSepHeightMobile'] . 'px;';
						$css .= '}';
					}
					if ( isset( $attr['bottomSepWidthMobile'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr['bottomSepWidthMobile'] . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
		}
		if ( isset( $attr['topSep'] ) && 'none' != $attr['topSep'] ) {
			if ( isset( $attr['topSepHeight'] ) || isset( $attr['topSepWidth'] ) ) {
				if ( isset( $attr['topSepHeight'] ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
						$css .= 'height:' . $attr['topSepHeight'] . 'px;';
					$css .= '}';
				}
				if ( isset( $attr['topSepWidth'] ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
						$css .= 'width:' . $attr['topSepWidth'] . '%;';
					$css .= '}';
				}
			}
			if ( isset( $attr['topSepHeightTablet'] ) || isset( $attr['topSepWidthTablet'] ) ) {
				$css .= '@media (min-width: 767px) and (max-width: 1024px) {';
					if ( isset( $attr['topSepHeightTablet'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr['topSepHeightTablet'] . 'px;';
						$css .= '}';
					}
					if ( isset( $attr['topSepWidthTablet'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr['topSepWidthTablet'] . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
			if ( isset( $attr['topSepHeightMobile'] ) || isset( $attr['topSepWidthMobile'] ) ) {
				$css .= '@media (max-width: 767px) {';
					if ( isset( $attr['topSepHeightMobile'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep {';
							$css .= 'height:' . $attr['topSepHeightMobile'] . 'px;';
						$css .= '}';
					}
					if ( isset( $attr['topSepWidthMobile'] ) ) {
						$css .= '#bb-layout-id' . $unique_id . ' .bb-row-layout-bottom-sep svg {';
							$css .= 'width:' . $attr['topSepWidthMobile'] . '%;';
						$css .= '}';
					}
				$css .= '}';
			}
		}
		if ( isset( $attr['topPadding'] ) || isset( $attr['bottomPadding'] ) || isset( $attr['leftPadding'] ) || isset( $attr['rightPadding'] ) || isset( $attr['minHeight'] ) ||  isset( $attr['maxWidth'] ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap {';
				if ( isset( $attr['topPadding'] ) ) {
					$css .= 'padding-top:' . $attr['topPadding'] . 'px;';
				}
				if ( isset( $attr['bottomPadding'] ) ) {
					$css .= 'padding-bottom:' . $attr['bottomPadding'] . 'px;';
				}
				if ( isset( $attr['leftPadding'] ) ) {
					$css .= 'padding-left:' . $attr['leftPadding'] . 'px;';
				}
				if ( isset( $attr['rightPadding'] ) ) {
					$css .= 'padding-right:' . $attr['rightPadding'] . 'px;';
				}
				if ( isset( $attr['minHeight'] ) ) {
					$css .= 'min-height:' . $attr['minHeight'] . 'px;';
				}
				if ( isset( $attr['maxWidth'] ) ) {
					$css .= 'max-width:' . $attr['maxWidth'] . 'px;';
					$css .= 'margin-left:auto;';
					$css .= 'margin-right:auto;';
				}
			$css .= '}';
		}
		if ( isset( $attr['overlay'] ) || isset( $attr['overlayBgImg'] ) || isset( $attr['overlaySecond'] ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-layout-overlay {';
				if ( isset( $attr['overlayOpacity'] ) ) {
					if ( $attr['overlayOpacity'] < 10 ) {
						$css .= 'opacity:0.0' . $attr['overlayOpacity'] . ';';
					} else if ( $attr['overlayOpacity'] >= 100 ) {
						$css .= 'opacity:1;';
					} else {
						$css .= 'opacity:0.' . $attr['overlayOpacity'] . ';';
					}
				}
				if ( isset( $attr['currentOverlayTab'] ) && 'grad' == $attr['currentOverlayTab'] ) {
					$type = ( isset( $attr['overlayGradType'] ) ? $attr['overlayGradType'] : 'linear');
					if ( 'radial' === $type ) {
						$angle = ( isset( $attr['overlayBgImgPosition'] ) ? 'at ' . $attr['overlayBgImgPosition'] : 'at center center' );
					} else {
						$angle = ( isset( $attr['overlayGradAngle'] ) ? $attr['overlayGradAngle'] . 'deg' : '180deg');
					}
					$loc = ( isset( $attr['overlayGradLoc'] ) ? $attr['overlayGradLoc'] : '0');
					$color = ( isset( $attr['overlay'] ) ? $attr['overlay'] : 'transparent');
					$locsecond = ( isset( $attr['overlayGradLocSecond'] ) ? $attr['overlayGradLocSecond'] : '100');
					$colorsecond = ( isset( $attr['overlaySecond'] ) ? $attr['overlaySecond'] : '#00B5E2');
					$css .= 'background-image: ' . $type . '-gradient(' . $angle. ', ' . $color . ' ' . $loc . '%, ' . $colorsecond . ' ' . $locsecond . '%);';
				} else {
					if ( isset( $attr['overlay'] ) ) {
						$css .= 'background-color:' . $attr['overlay'] . ';';
					}
					if ( isset( $attr['overlayBgImg'] ) ) {
						$css .= 'background-image:url(' . $attr['overlayBgImg'] . ');';
						$css .= 'background-size:' . ( isset( $attr['overlayBgImgSize'] ) ? $attr['overlayBgImgSize'] : 'cover' ) . ';';
						$css .= 'background-position:' . ( isset( $attr['overlayBgImgPosition'] ) ? $attr['overlayBgImgPosition'] : 'center center' ) . ';';
						$css .= 'background-attachment:' . ( isset( $attr['overlayBgImgAttachment'] ) ? $attr['overlayBgImgAttachment'] : 'scroll' ) . ';';
						$css .= 'background-repeat:' . ( isset( $attr['overlayBgImgRepeat'] ) ? $attr['overlayBgImgRepeat'] : 'no-repeat' ) . ';';
					}
				}
				if ( isset( $attr['overlayBlendMode'] ) ) {
					$css .= 'mix-blend-mode:' . $attr['overlayBlendMode'] . ';';
				}
			$css .= '}';
		}
		if ( isset( $attr['topPaddingM'] ) || isset( $attr['bottomPaddingM'] ) || isset( $attr['leftPaddingM'] ) || isset( $attr['rightPaddingM'] ) || isset( $attr['topMarginM'] ) || isset( $attr['bottomMarginM'] ) ) {
			$css .= '@media (max-width: 767px) {';
				if ( isset( $attr['topMarginM'] ) || isset( $attr['bottomMarginM'] ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' {';
						if ( isset( $attr['topMarginM'] ) ) {
							$css .= 'margin-top:' . $attr['topMarginM'] . 'px;';
						}
						if ( isset( $attr['bottomMarginM'] ) ) {
							$css .= 'margin-bottom:' . $attr['bottomMarginM'] . 'px;';
						}
					$css .= '}';
				}
				if ( isset( $attr['topPaddingM'] ) || isset( $attr['bottomPaddingM'] ) || isset( $attr['leftPaddingM'] ) || isset( $attr['rightPaddingM'] ) ) {
					$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap {';
					if ( isset( $attr['topPaddingM'] ) ) {
						$css .= 'padding-top:' . $attr['topPaddingM'] . 'px;';
					}
					if ( isset( $attr['bottomPaddingM'] ) ) {
						$css .= 'padding-bottom:' . $attr['bottomPaddingM'] . 'px;';
					}
					if ( isset( $attr['leftPaddingM'] ) ) {
						$css .= 'padding-left:' . $attr['leftPaddingM'] . 'px;';
					}
					if ( isset( $attr['rightPaddingM'] ) ) {
						$css .= 'padding-right:' . $attr['rightPaddingM'] . 'px;';
					}
					$css .= '}';
				}
			
			$css .= '}';
		}
		return $css;
	}
	/**
	 * Builds CSS for column layout block.
	 * 
	 * @param object $attr the blocks attr.
	 * @param string $unique_id the blocks parent attr ID.
	 * @param number $column_key the blocks key.
	 */
	function column_layout_css( $attr, $unique_id, $column_key ) {
		$css = '';
		if ( isset( $attr->topPadding ) || isset( $attr->bottomPadding ) || isset( $attr->leftPadding ) || isset( $attr->rightPadding ) || isset( $attr->topMargin ) || isset( $attr->bottomMargin ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap > .inner-column-' . $column_key . ' > .bb-inside-inner-col {';
				if ( isset( $attr->topPadding ) ) {
					$css .= 'padding-top:' . $attr->topPadding . 'px;';
				}
				if ( isset( $attr->bottomPadding ) ) {
					$css .= 'padding-bottom:' . $attr->bottomPadding . 'px;';
				}
				if ( isset( $attr->leftPadding ) ) {
					$css .= 'padding-left:' . $attr->leftPadding . 'px;';
				}
				if ( isset( $attr->rightPadding ) ) {
					$css .= 'padding-right:' . $attr->rightPadding . 'px;';
				}
				if ( isset( $attr->topMargin ) ) {
					$css .= 'margin-top:' . $attr->topMargin . 'px;';
				}
				if ( isset( $attr->bottomMargin ) ) {
					$css .= 'margin-bottom:' . $attr->bottomMargin . 'px;';
				}
			$css .= '}';
		}
		if ( isset( $attr->topPaddingM ) || isset( $attr->bottomPaddingM ) || isset( $attr->leftPaddingM ) || isset( $attr->rightPaddingM ) || isset( $attr->topMarginM ) || isset( $attr->bottomMarginM ) ) {
			$css .= '@media (max-width: 767px) {';
				$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap > .inner-column-' . $column_key . ' > .bb-inside-inner-col {';
				if ( isset( $attr->topPaddingM ) ) {
					$css .= 'padding-top:' . $attr->topPaddingM . 'px;';
				}
				if ( isset( $attr->bottomPaddingM ) ) {
					$css .= 'padding-bottom:' . $attr->bottomPaddingM . 'px;';
				}
				if ( isset( $attr->leftPaddingM ) ) {
					$css .= 'padding-left:' . $attr->leftPaddingM . 'px;';
				}
				if ( isset( $attr->rightPaddingM ) ) {
					$css .= 'padding-right:' . $attr->rightPaddingM . 'px;';
				}
				if ( isset( $attr->topMarginM ) ) {
					$css .= 'margin-top:' . $attr->topMarginM . 'px;';
				}
				if ( isset( $attr->bottomMarginM ) ) {
					$css .= 'margin-bottom:' . $attr->bottomMarginM . 'px;';
				}
				$css .= '}';		
			$css .= '}';
		}
		return $css;
	}
	/**
	 * Builds CSS for column layout block.
	 * 
	 * @param array  $attr the blocks attr.
	 * @param string $unique_id the blocks parent attr ID.
	 * @param number $column_key the blocks key.
	 */
	function column_layout_array_css( $attr, $unique_id, $column_key ) {
		$css = '';
		if ( isset( $attr['topPadding'] ) || isset( $attr['bottomPadding'] ) || isset( $attr['leftPadding'] ) || isset( $attr['rightPadding'] ) || isset( $attr['topMargin'] ) || isset( $attr['bottomMargin'] ) ) {
			$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap > .inner-column-' . $column_key . ' > .bb-inside-inner-col {';
				if ( isset( $attr['topPadding'] ) ) {
					$css .= 'padding-top:' . $attr['topPadding'] . 'px;';
				}
				if ( isset( $attr['bottomPadding'] ) ) {
					$css .= 'padding-bottom:' . $attr['bottomPadding'] . 'px;';
				}
				if ( isset( $attr['leftPadding'] ) ) {
					$css .= 'padding-left:' . $attr['leftPadding'] . 'px;';
				}
				if ( isset( $attr['rightPadding'] ) ) {
					$css .= 'padding-right:' . $attr['rightPadding'] . 'px;';
				}
				if ( isset( $attr['topMargin'] ) ) {
					$css .= 'margin-top:' . $attr['topMargin'] . 'px;';
				}
				if ( isset( $attr['bottomMargin'] ) ) {
					$css .= 'margin-bottom:' . $attr['bottomMargin'] . 'px;';
				}
			$css .= '}';
		}
		if ( isset( $attr['topPaddingM'] ) || isset( $attr['bottomPaddingM'] ) || isset( $attr['leftPaddingM'] ) || isset( $attr['rightPaddingM'] ) || isset( $attr['topMarginM'] ) || isset( $attr['bottomMarginM'] ) ) {
			$css .= '@media (max-width: 767px) {';
				$css .= '#bb-layout-id' . $unique_id . ' > .bb-row-column-wrap > .inner-column-' . $column_key . ' > .bb-inside-inner-col {';
				if ( isset( $attr['topPaddingM'] ) ) {
					$css .= 'padding-top:' . $attr['topPaddingM'] . 'px;';
				}
				if ( isset( $attr['bottomPaddingM'] ) ) {
					$css .= 'padding-bottom:' . $attr['bottomPaddingM'] . 'px;';
				}
				if ( isset( $attr['leftPaddingM'] ) ) {
					$css .= 'padding-left:' . $attr['leftPaddingM'] . 'px;';
				}
				if ( isset( $attr['rightPaddingM'] ) ) {
					$css .= 'padding-right:' . $attr['rightPaddingM'] . 'px;';
				}
				if ( isset( $attr['topMarginM'] ) ) {
					$css .= 'margin-top:' . $attr['topMarginM'] . 'px;';
				}
				if ( isset( $attr['bottomMarginM'] ) ) {
					$css .= 'margin-bottom:' . $attr['bottomMarginM'] . 'px;';
				}
				$css .= '}';		
			$css .= '}';
		}
		return $css;
	}

	public function enqueue_frontend_assets() {
		wp_enqueue_style( 'better-builder-blocks', BetterCore()->assets_url . 'css/gutenberg/blocks.style.css' );
		wp_add_inline_style( 'better-builder-blocks', $this->dimensions_styles() );
	}

    public function enqueue_scripts() {
        wp_enqueue_script( 'backgroundvideo' );
		wp_enqueue_script( 'better.contentsection' );
		wp_enqueue_script( 'okvideo' );
		wp_enqueue_script( 'skrollr' );
		wp_enqueue_script( 'videoBG' );

		// Masonry block.
		wp_enqueue_script(
			'better-masonry',
			BetterCore()->assets_url . 'shortcodes/masonry/block-gallery-masonry.js',
			array( 'jquery', 'masonry', 'imagesloaded' ),
			BetterCore()->_version,
			true
		);

		// Carousel block.
		wp_enqueue_script(
			'flickity',
			'https://unpkg.com/flickity@2.2.0/dist/flickity.pkgd.min.js',
			array( 'jquery' ),
			BetterCore()->_version,
			true
		);
    }

	public function editor_assets() {
		foreach ( BetterCore()->shortcodes->list as $key => $shortcode ) {
			$shortcode->map( $this, 'editor_asset' );
		}
	}

	public function blocks_admin_editor_width() {
		$sm_template = tr_options_field('better_builder.small_template');
		$lg_template = tr_options_field('better_builder.large_template');
		$post_default = tr_options_field('better_builder.post_default');
		$page_default = tr_options_field('better_builder.page_default');
		$editor_width = tr_options_field('better_builder.enable_editor_width');

		if ( 'yes' === $editor_width ) {
			$add_size = 30;
			$post_type = get_post_type();
			if( ! empty( $page_default ) && ! empty( $post_default ) ) {
				if ( 'page' === $post_type ) {
					$defualt_size_type = $page_default;
				} else {
					$defualt_size_type = $post_default;
				}
			} else {
				$defualt_size_type = 'sidebar';
			}
			if ( ! empty( $sm_template ) ) {
				$sidebar_size = $sm_template + $add_size;
			} else {
				$sidebar_size = 750;
			}
			if ( ! empty( $lg_template ) ) {
				$nosidebar_size = $lg_template + $add_size;
			} else {
				$nosidebar_size = 1140 + $add_size;
			}
			if ( 'sidebar' == $defualt_size_type ) {
				$default_size = $sidebar_size;
			} elseif ( 'fullwidth' == $defualt_size_type ) {
				$default_size = 'none';
			} else {
				$default_size = $nosidebar_size;
			}
			if ( 'none' === $default_size ) {
				$jssize = 2000;
			} else {
				$jssize = $default_size;
			}

			echo '<style type="text/css" id="bb-block-editor-width">';
			echo 'body.block-editor-page.bb-editor-width-default .editor-post-title__block,
			body.block-editor-page.bb-editor-width-default .editor-default-block-appender,
			body.block-editor-page.bb-editor-width-default .editor-block-list__block,
			body.block-editor-page.bb-editor-width-default .wp-block {
				max-width: ' . esc_attr( $default_size ) . ( is_numeric( $default_size ) ? 'px' : '' ) . ';
			}';
			echo 'body.block-editor-page.bb-editor-width-sidebar .editor-post-title__block,
			body.block-editor-page.bb-editor-width-sidebar .editor-default-block-appender,
			body.block-editor-page.bb-editor-width-sidebar .editor-block-list__block,
			body.block-editor-page.bb-editor-width-sidebar .wp-block {
				max-width: ' . esc_attr( $sidebar_size ) . 'px;
			}';
			echo 'body.block-editor-page.bb-editor-width-nosidebar .editor-post-title__block,
			body.block-editor-page.bb-editor-width-nosidebar .editor-default-block-appender,
			body.block-editor-page.bb-editor-width-nosidebar .editor-block-list__block,
			body.block-editor-page.bb-editor-width-nosidebar .wp-block {
				max-width: ' . esc_attr( $nosidebar_size ) . 'px;
			}';
			echo 'body.block-editor-page.bb-editor-width-fullwidth .editor-post-title__block,
			body.block-editor-page.bb-editor-width-fullwidth .editor-default-block-appender,
			body.block-editor-page.bb-editor-width-fullwidth .editor-block-list__block,
			body.block-editor-page.bb-editor-width-fullwidth .wp-block {
				max-width: none;
			}';
			echo 'body.block-editor-page .editor-block-list__layout .editor-block-list__block[data-align=wide],
			body.block-editor-page .editor-block-list__layout .wp-block[data-align=wide] {
				width: auto;
				max-width: ' . esc_attr( $nosidebar_size + 200 ) . 'px;
			}';
			
			echo 'body.block-editor-page .editor-block-list__layout .editor-block-list__block[data-align=full],
			body.block-editor-page .editor-block-list__layout .wp-block[data-align=full] {
				max-width: none;
			}';
			echo '</style>';
			echo "<script> var bb_blocks_sidebar_size = ".$sidebar_size."; var bb_blocks_nosidebar_size = ".$nosidebar_size."; var bb_blocks_default_size = ".$jssize.";</script>";
		}
	}

	public function editor_asset( $shortcode, $settings ) {
        $shortcode_name = str_replace( '_', '', str_replace( 'better_', '', $settings['shortcode'] ) );
        $url = BetterCore()->assets_url . 'js/gutenberg/' . $shortcode_name . '.js';
        $path = BetterCore()->dir . '/assets/js/gutenberg/' . $shortcode_name . '.js';

		wp_enqueue_script( 'better-builder-blocks-js', BetterCore()->assets_url . 'js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-edit-post', 'wp-api' ), BetterCore()->_version, true );

		wp_enqueue_style( 'better-builder-blocks-css', BetterCore()->assets_url . 'css/gutenberg/blocks.editor.css', array( 'wp-edit-blocks' ) );

		wp_enqueue_style( 'better-admin-gutenberg', BetterCore()->assets_url . 'css/gutenberg/gutenberg.css', null );

		//wp_enqueue_script( 'gutenberg_' . $settings['shortcode'], $url, array( 'wp-blocks', 'wp-element' ) );

		wp_localize_script( 'better-builder-blocks-js', 'better_script_vars_' . $shortcode_name, array(
			'shortcode_name' => 'gutenberg_' . $settings['shortcode'],
			'shortcode_title' => $settings['name']
		) );

		$icon_source = array();
		$packs = BetterSC_Icon::get_icon_sources();

		foreach ( $packs as $key => $value ) {
			$icon_source[ $key ] = $value['title'] . ' (' . $value['count'] . ')';
		}

		$sm_template = tr_options_field('better_builder.small_template');
		$lg_template = tr_options_field('better_builder.large_template');
		$post_default = tr_options_field('better_builder.post_default');
		$page_default = tr_options_field('better_builder.page_default');
		$editor_width = tr_options_field('better_builder.enable_editor_width');
		
		$sidebar_size = 750;
		$nosidebar_size = 1140;
		$jssize = 2000;
		
		if ( 'yes' === $editor_width ) {
			$add_size = 30;
			$post_type = get_post_type();
			if( ! empty( $page_default ) && ! empty( $post_default ) ) {
				if ( 'page' === $post_type ) {
					$defualt_size_type = $page_default;
				} else {
					$defualt_size_type = $post_default;
				}
			} else {
				$defualt_size_type = 'sidebar';
			}
			if ( ! empty( $sm_template ) ) {
				$sidebar_size = $sm_template + $add_size;
			} else {
				$sidebar_size = 750;
			}
			if ( ! empty( $lg_template ) ) {
				$nosidebar_size = $lg_template + $add_size;
			} else {
				$nosidebar_size = 1140 + $add_size;
			}
			if ( 'sidebar' == $defualt_size_type ) {
				$default_size = $sidebar_size;
			} elseif ( 'fullwidth' == $defualt_size_type ) {
				$default_size = 'none';
			} else {
				$default_size = $nosidebar_size;
			}
			if ( 'none' === $default_size ) {
				$jssize = 2000;
			} else {
				$jssize = $default_size;
			}
		}

		wp_localize_script( 'better-builder-blocks-js', 'better_script_vars', array(
			'icon_source' => $icon_source,
			'better_templates' => better_locate_available_plugin_templates( '/template/' ),
			'better_templates_cpt' => better_locate_available_plugin_templates( '/custom-post/post/' ),
			'better_image_sizes' => array_merge( array( '' => '' ), array_combine( get_intermediate_image_sizes(), get_intermediate_image_sizes() ) ),
			'better_giphy_api' => tr_options_field('better_builder.giphy_api_key'),
			'better_tracks' => BetterCore()->custom_post_types->get_posts_list( 'better_audio' ),
			'assets_url' => BetterCore()->assets_url,
			'unsplash_client_id' => 'cc94fd94a4ed85dabeeb55269d8633970bfe65edba14cfc6407a99ab659ec1f4',
			'css_animation' => Better_Shortcode::css_animation(),
			'sidebar_size' => $sidebar_size,
			'nosidebar_size' => $nosidebar_size,
			'default_size' => $jssize,
		) );

		if ( ! $editor_width || 'no' === $editor_width ) {
			$plugins = array('better-editor-width');
		} else {
			$plugins = array();
		}
		wp_enqueue_script( 'better-blocks-deregister-js', BetterCore()->assets_url . 'js/blocks-deregister.js', array( 'wp-blocks' ), BetterCore()->_version, true );
		wp_localize_script( 'better-blocks-deregister-js', 'better_deregister_params', array(
			'dergisterplugins' => $plugins,
		) );
	}

    public function server_side() {
        foreach ( BetterCore()->shortcodes->list as $key => $shortcode ) {
            $shortcode_name = str_replace( '_', '', str_replace( 'BetterSC_', '', $key ) );
            $atts = $shortcode->map( $this, 'map_fields' );

            register_block_type( 'better-gutenberg/' . strtolower( $shortcode_name ), array(
				'style' => 'betterbuilder-frontend',
                'attributes' => $atts,
                'render_callback' => array( $shortcode, 'render_shortcode' )
            ) );
        }
    }

    public function map_fields( $shortcode, $settings ) {
        $fields = array();

        foreach ( $settings['fields'] as $key => $field ) {
            $fields[ $key ] = $this->map_field( $key, $field );
        }

        return $fields;
    }

    public function map_field( $key, $field ) {
        $param = array();

        if ( isset( $field['default'] ) ) {
            $param['default'] = $field['default'];
        }

        switch ( $field['type'] ) {
            case 'text':
            case 'post_type':
            case 'taxonomy':
                $param['type'] = 'string';
				break;
				
			case 'array':
            case 'terms':
                $param['type'] = 'array';
                break;

            case 'textarea':
                $param['type'] = 'string';
                break;

            case 'dropdown':
            case 'select':
                $param['type'] = 'string';
				break;
				
			case 'template':
				$param['type'] = 'string';
				break;

			case 'number':
			case 'range':
                $param['type'] = 'number';
                break;

            case 'color':
                $param['type'] = 'string';
                break;

            case 'font_family':
                $param['type'] = 'string';
                break;

            case 'spacing':
                $param['type'] = 'string';
                break;

            case 'yes_no_button':
            case 'checkbox':
                $param['type'] = 'boolean';
                break;

            case 'image':
                $param['type'] = 'number';
                break;

            case 'gallery':
                $param['type'] = 'string';
                break;

            case 'file':
                $param['type'] = 'string';
                break;

            default:
                # code...
                break;
        }

        return $param;
    }

}
