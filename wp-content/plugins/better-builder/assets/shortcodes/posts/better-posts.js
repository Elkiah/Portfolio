jQuery(document).ready(function($) {
	
	function betterInitSwiper( $slider ) {
		var $sliderContainer = $slider.children( '.swiper-container' ).first();
		var lgItems = $slider.data( 'lg-items' ) ? $slider.data( 'lg-items' ) : 1;
		var mdItems = $slider.data( 'md-items' ) ? $slider.data( 'md-items' ) : lgItems;
		var smItems = $slider.data( 'sm-items' ) ? $slider.data( 'sm-items' ) : mdItems;
		var xsItems = $slider.data( 'xs-items' ) ? $slider.data( 'xs-items' ) : smItems;

		var lgGutter = $slider.data( 'lg-gutter' ) ? $slider.data( 'lg-gutter' ) : 0;
		var mdGutter = $slider.data( 'md-gutter' ) ? $slider.data( 'md-gutter' ) : lgGutter;
		var smGutter = $slider.data( 'sm-gutter' ) ? $slider.data( 'sm-gutter' ) : mdGutter;
		var xsGutter = $slider.data( 'xs-gutter' ) ? $slider.data( 'xs-gutter' ) : smGutter;

		var vertical = $slider.data( 'vertical' );
		var loop = $slider.data( 'loop' );
		var autoPlay = $slider.data( 'autoplay' );
		var speed = $slider.data( 'speed' );
		var nav = $slider.data( 'nav' );
		var pagination = $slider.data( 'pagination' );
		var paginationType = $slider.data( 'pagination-type' );
		var paginationNumber = $slider.data( 'pagination-number' );
		var wrapTools = $slider.data( 'wrap-tools' );
		var mouseWheel = $slider.data( 'mousewheel' );
		var effect = $slider.data( 'effect' );
		var slideWrap = $slider.data( 'slide-wrap' );

		if ( slideWrap ) {
			$slider.children( '.swiper-container' )
				.children( '.swiper-wrapper' )
				.children( 'div' )
				.wrap( "<div class='swiper-slide'></div>" );
		}

		var slidePerView = $slider.data( 'slide-per-view' );

		if ( slidePerView ) {
			var options = {
				slidesPerView: 'auto',
				freeMode: true,
				spaceBetween: lgGutter,
				breakpoints: {
					767: {
						spaceBetween: xsGutter
					},
					990: {
						spaceBetween: smGutter
					},
					1199: {
						spaceBetween: mdGutter
					}
				}
			};
		} else {
			var options = {
				slidesPerView: lgItems,
				spaceBetween: lgGutter,
				breakpoints: {
					// when window width is <=
					767: {
						slidesPerView: xsItems,
						spaceBetween: xsGutter
					},
					990: {
						slidesPerView: smItems,
						spaceBetween: smGutter
					},
					1199: {
						slidesPerView: mdItems,
						spaceBetween: mdGutter
					}
				}
			};
		}

		if ( speed ) {
			options.speed = speed;
		}

		// Maybe: fade, flip
		if ( effect ) {
			options.effect = effect;
		}

		if ( loop ) {
			options.loop = true;
		}

		if ( autoPlay ) {
			options.autoplay = autoPlay;
			options.autoplayDisableOnInteraction = false;
		}

		var $wrapTools;

		if ( wrapTools ) {
			$wrapTools = $( '<div class="swiper-tools"></div>' );

			$slider.append( $wrapTools );
		}

		if ( nav ) {
			var $swiperPrev = $( '<div class="swiper-nav-button swiper-button-prev"><i class="nav-button-icon"></i></div>' );
			var $swiperNext = $( '<div class="swiper-nav-button swiper-button-next"><i class="nav-button-icon"></i></div>' );

			if ( $wrapTools ) {
				$wrapTools.append( $swiperPrev ).append( $swiperNext );
			} else {
				$slider.append( $swiperPrev ).append( $swiperNext );
			}

			options.prevButton = $swiperPrev;
			options.nextButton = $swiperNext;
		}

		if ( pagination ) {
			var $swiperPagination = $( '<div class="swiper-pagination"></div>' );
			$slider.addClass( 'has-pagination' );

			if ( $wrapTools ) {
				$wrapTools.append( $swiperPagination );
			} else {
				$slider.append( $swiperPagination );
			}

			//var $swiperPagination        = $slider.children( '.swiper-pagination' );
			options.pagination = $swiperPagination;
			options.paginationClickable = true;
			options.onPaginationRendered = function( swiper ) {
				var total = swiper.slides.length;
				if ( total <= options.slidesPerView ) {
					$swiperPagination.hide();
				} else {
					$swiperPagination.show();
				}
			};

			if ( paginationType ) {
				options.paginationType = paginationType;
			}

			if ( $slider.hasClass( 'pagination-style-4' ) ) {
				options.paginationType = 'fraction';
			}
		}

		if ( paginationNumber ) {
			options.paginationBulletRender = function( swiper, index, className ) {
				return '<span class="' + className + '">' + (
					index + 1
				) + '</span>';
			}
		}

		if ( mouseWheel ) {
			options.mousewheelControl = true;
		}

		if ( vertical ) {
			options.direction = 'vertical'
		}

		var $swiper = new Swiper( $sliderContainer, options );
	}

	var animateQueueDelay = 200,
		queueResetDelay;

	/**
	 * Global ajaxBusy = false
	 * Desc: Status of ajax
	 */
	var ajaxBusy = false;
	$( document ).ajaxStart( function() {
		ajaxBusy = true;
	} ).ajaxStop( function() {
		ajaxBusy = false;
	} );

	function processItemQueue( itemQueue, queueDelay, queueTimer, queueResetDelay ) {
		clearTimeout( queueResetDelay );
		queueTimer = window.setInterval( function() {
			if ( itemQueue !== undefined && itemQueue.length ) {
				$( itemQueue.shift() ).addClass( 'animate' );
				processItemQueue();
			} else {
				window.clearInterval( queueTimer );
			}
		}, queueDelay );
	}

	betterPostGridInit();

	$( '.bb-swiper' ).each( function() {
		betterInitSwiper( $( this ) );
	} );

	var resizeTimer;

	// Add isotope-hidden class for filtered items.
	if ( typeof Isotope != 'undefined' ) {
		// Add isotope-hidden class for filtered items.
		var itemReveal = Isotope.Item.prototype.reveal,
			itemHide   = Isotope.Item.prototype.hide;
	
		Isotope.Item.prototype.reveal = function() {
			itemReveal.apply( this, arguments );
			$( this.element )
				.removeClass( 'isotope-hidden' );
		};
	
		Isotope.Item.prototype.hide = function() {
			itemHide.apply( this, arguments );
			$( this.element )
				.addClass( 'isotope-hidden' );
		};
	}

	function betterPostGridInit() {
		$('.bb-block-post-grid').each( function() {
			var $el = $( this );
			var $grid = $el.find( '.bb-post-grid-items' );
			var $gridData;
			var $items = $grid.children( '.bb-post-item' );
			var gutter = $el.data( 'gutter' ) ? $el.data( 'gutter' ) : 0;
			if ( $el.data( 'type' ) == 'masonry' ) {
				var $isotopeOptions = {
					itemSelector: '.bb-post-item',
					percentPosition: true,
				};
	
				if ( $el.data( 'grid-fitrows' ) ) {
					$isotopeOptions.layoutMode = 'fitRows';
				} else {
					$isotopeOptions.layoutMode = 'packery';
					$isotopeOptions.packery = {
						// Use outer width of grid-sizer for columnWidth.
						columnWidth: '.grid-sizer'
					}
				}
	
				if ( $isotopeOptions.layoutMode === 'fitRows' ) {
					// Set gutter for fit rows layout.
					$isotopeOptions.fitRows = {};
					$isotopeOptions.fitRows.gutter = gutter;
				} else if ( $isotopeOptions.layoutMode === 'packery' ) {
					$isotopeOptions.packery.gutter = gutter;
				} else {
					// Set gutter for masonry layout.
					//$isotopeOptions.masonry.gutter = gutter;
					$isotopeOptions.masonry = {
						gutter: gutter
					}
				}
	
				// Remove default transition if grid has custom animation.
				if ( $grid.hasClass( 'has-animation' ) ) {
					$isotopeOptions.transitionDuration = 0;
				}
	
				$( window ).resize( function() {
					betterGridMasonryCalculateSize( $el, $grid, $isotopeOptions );
					clearTimeout( resizeTimer );
					resizeTimer = setTimeout( function() {
						// Run code here, resizing has "stopped"
						betterGridMasonryCalculateSize( $el, $grid, $isotopeOptions );
					}, 300 );
				} );

				betterGridMasonryCalculateSize( $el, $grid );
	
				$gridData = $grid.imagesLoaded( function() {
					// init Isotope after all images have loaded
					$grid.isotope( $isotopeOptions );
	
					// if ( $el.data( 'match-height' ) ) {
					// 	$items.matchHeight();
					// }

					$( document ).trigger( 'betterGridInit', [ $el, $grid, $isotopeOptions ] );
				} );
	
				$gridData.one( 'arrangeComplete', function() {
					betterInitGridAnimation( $grid, $items );
					//betterGridFilterCount( $el, $grid );
				} );
			} else {
				betterInitGridAnimation( $grid, $items );
			}
	
			betterGridFilterHandler( $el, $grid );

			if ( $el.data( 'pagination' ) == 'loadmore' ) {
				$el.children( '.bb-grid-pagination' ).find( '.bb-grid-loadmore-btn' ).on( 'click', function( e ) {
					e.preventDefault();
					if ( ! ajaxBusy ) {
						$( this ).hide();
						var $queryInput = $el.find( '.bb-grid-query' )
												.first();
						var query = jQuery.parseJSON( $queryInput.val() );
	
						query.paged ++;
						$queryInput.val( JSON.stringify( query ) );
						betterAjaxPostQuery( $el, $grid );
					}
				} );
			} else if ( $el.data( 'pagination' ) == 'infinite' ) {
				$( '.bb-grid-pagination', $el ).waypoint( function( direction ) {
					if ( direction === 'down' && ! ajaxBusy ) {
						var $queryInput = $el.find( '.bb-grid-query' )
												.first();
						var query = jQuery.parseJSON( $queryInput.val() );
	
						query.paged ++;
						$queryInput.val( JSON.stringify( query ) );
	
						betterAjaxPostQuery( $el, $grid );
					}
				}, {
					offset: '100%'
				} )
			}
	
			$( document ).on( 'betterGridInfinityLoad', function( e ) {
				var $queryInput = $el.find( '.bb-grid-query' ).first();
				var query = jQuery.parseJSON( $queryInput.val() );
				query.paged = 1;
				$queryInput.val( JSON.stringify( query ) );
	
				betterAjaxPostQuery( $el, $grid, true );
			} );
	
			$el.addClass( 'grid-loaded' );
		} );
	}

	/**
	 * Calculate size for grid classic + masonry.
	 */
	function betterGridMasonryCalculateSize( $el, $grid, $isotopeOptions ) {
		var windowWidth = $( window ).width();
		var $column = 1;
		var lgColumns = $el.data( 'lg-columns' ) ? $el.data( 'lg-columns' ) : 1;
		var mdColumns = $el.data( 'md-columns' ) ? $el.data( 'md-columns' ) : lgColumns;
		var smColumns = $el.data( 'sm-columns' ) ? $el.data( 'sm-columns' ) : mdColumns;
		var xsColumns = $el.data( 'xs-columns' ) ? $el.data( 'xs-columns' ) : smColumns;
		if ( windowWidth >= 1200 ) {
			$column = lgColumns;
		} else if ( windowWidth >= 961 ) {
			$column = mdColumns;
		} else if ( windowWidth >= 641 ) {
			$column = smColumns;
		} else {
			$column = xsColumns;
		}

		var $gridWidth = $grid[ 0 ].getBoundingClientRect().width;
		var $gutter = $el.data( 'gutter' ) ? $el.data( 'gutter' ) : 0;

		var $totalGutter = (
							$column - 1
						) * $gutter;

		var $columnWidth = (
							$gridWidth - $totalGutter
						) / $column;

		//$columnWidth = Math.floor( $columnWidth );

		if ( $column > 1 ) {
			var $columnWidth2 = $columnWidth * 2 + $gutter;
		} else {
			var $columnWidth2 = $columnWidth;
		}

		$grid.children( '.grid-sizer' ).css( {
			'width': $columnWidth + 'px'
		} );

		var $columnHeight = $columnWidth;
		var $columnHeight2 = $columnHeight;
		var ratio = $el.data( 'grid-ratio' );

		if ( ratio ) {
			var res = ratio.split( ':' );
			var ratioW = parseFloat( res[ 0 ] );
			var ratioH = parseFloat( res[ 1 ] );

			$columnHeight = (
								$columnWidth * ratioH
							) / ratioW;

			$columnHeight = Math.floor( $columnHeight );

			if ( $column > 1 ) {
				$columnHeight2 = $columnHeight * 2 + $gutter;
			} else {
				$columnHeight2 = $columnHeight;
			}
		}

		$grid.children( '.bb-post-item' ).each( function() {
			if ( $( this ).data( 'width' ) == '2' ) {
				$( this ).css( {
					'width': $columnWidth2 + 'px'
				} );
			} else {
				$( this ).css( {
					'width': $columnWidth + 'px'
				} );
			}
			if ( ratio ) {
				if ( $( this ).data( 'height' ) == '2' ) {
					$( this ).css( {
						'height': $columnHeight2 + 'px'
					} );
				} else {
					$( this ).css( {
						'height': $columnHeight + 'px'
					} );
				}
			}
		} );

		if ( $isotopeOptions ) {
			$grid.isotope( 'layout', $isotopeOptions );
		}
	}

	/**
	 * Load post infinity from db.
	 */
	function betterAjaxPostQuery( $wrapper, $grid, reset ) {
		var loader = $wrapper.children( '.bb-grid-pagination' ).find( '.bb-grid-loader' );

		loader.css( {
			'display': 'inline-block'
		} );

		setTimeout( function() {
			var $queryInput = $wrapper.find( '.bb-grid-query' )
									.first(),
				query       = jQuery.parseJSON( $queryInput.val() ),
				_data       = $.param( query );

			$.ajax( {
				url: $better_posts.ajaxurl,
				type: 'POST',
				data: _data,
				dataType: 'json',
				success: function( results ) {

					if ( results.found_posts ) {
						query.found_posts = results.found_posts;
					}

					if ( results.max_num_pages ) {
						query.max_num_pages = results.max_num_pages;
					}

					if ( results.count ) {
						query.count = results.count;
					}

					$queryInput.val( JSON.stringify( query ) );

					var html = results.template;

					var $items = $( html );

					if ( reset == true ) {
						$grid.children( '.bb-post-item' ).remove();
					}

					if ( $wrapper.data( 'type' ) == 'masonry' ) {
						$grid.isotope()
							.append( $items )
							.isotope( 'appended', $items )
							.imagesLoaded()
							.always( function() {
								$grid.isotope( 'layout' );
								// Re run match height for all items.
								// if ( $wrapper.data( 'match-height' ) ) {
								// 	$grid.children( '.bb-post-item' ).matchHeight();
								// }
							} );
						//betterGridFilterCount( $wrapper, $grid );
						betterGridMasonryCalculateSize( $wrapper, $grid );
					} else if ( $wrapper.data( 'type' ) == 'swiper' ) {
						var $slider = $wrapper.find( '.swiper-container' )[ 0 ].swiper;
						$slider.appendSlide( $items );
						$slider.update();
					} else {
						$grid.append( $items );
					}
					betterInitGridAnimation( $grid, $items );
					//betterInitGalleryForNewItems( $grid, $items );
					//betterInitGridOverlay( $grid, $items );
					betterHidePaginationIfEnd( $wrapper, query );

					loader.hide();
				}
			} );
		}, 500 );
	}

	/**
	 * Remove pagination if has no posts anymore
	 *
	 * @param $el
	 * @param query
	 *
	 */
	function betterHidePaginationIfEnd( $el, query ) {
		if ( query.found_posts <= (
			query.paged * query.posts_per_page
		) ) {

			if ( $el.data( 'pagination' ) === 'loadmore_alt' ) {
				var _loadmoreBtn = $( $el.data( 'pagination-custom-button-id' ) );

				_loadmoreBtn.hide();
			} else {
				$el.children( '.bb-grid-pagination' ).hide();
			}

			$el.children( '.bb-grid-messages' ).show( 1 );
			setTimeout( function() {
				$el.children( '.bb-grid-messages' ).remove();
			}, 5000 );
		} else {
			if ( $el.data( 'pagination' ) === 'loadmore_alt' ) {
				var _loadmoreBtn = $( $el.data( 'pagination-custom-button-id' ) );

				_loadmoreBtn.show();
			} else {
				$el.children( '.bb-grid-pagination' ).show();
				$el.children( '.bb-grid-pagination' ).find( '.bb-grid-loadmore-btn' ).show();
			}

		}
	}

	function betterGridFilterHandler( $el, $grid ) {
		$el.children( '.bb-filter-button' ).on( 'click', '.filter-btn', function() {
			if ( $( this ).hasClass( 'active' ) ) {
				return;
			}

			if ( $el.data( 'filter-type' ) == 'ajax' ) {
				var filterValue = $( this ).attr( 'data-filter' );

				var $queryInput = $el.find( '.bb-grid-query' ).first();
				var query = jQuery.parseJSON( $queryInput.val() );
				if ( filterValue === '*' ) {
					query.extra_taxonomy = '';
				} else {
					query.extra_taxonomy = $( this ).attr( 'data-ajax-filter' );
				}

				$queryInput.val( JSON.stringify( query ) );

				$( document ).trigger( 'betterGridInfinityLoad', $el );

				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );
			} else {
				var filterValue = $( this ).attr( 'data-filter' );
				if ( $el.data( 'type' ) == 'masonry' ) {
					$grid.children( '.bb-post-item' ).each( function() {
						$( this ).removeClass( 'animate' );
					} );

					$grid.isotope( {
						filter: filterValue
					} );

					var itemQueue = [],
						queueDelay = animateQueueDelay,
						queueTimer;

					if ( $grid.hasClass( 'has-animation' ) ) {
						$grid.children( '.bb-post-item:not(.isotope-hidden)' )
							.each( function() {
								itemQueue.push( $( this ) );

								processItemQueue( itemQueue, queueDelay, queueTimer );
								queueDelay += 250;

								queueResetDelay = setTimeout( function() {
									queueDelay = animateQueueDelay;
								}, animateQueueDelay );
							} );
					}
				} else if ( $el.data( 'type' ) == 'swiper' ) {
					filterValue = filterValue.replace( '.', '' );
					$grid.children( '.bb-post-item' ).each( function() {
						if ( filterValue == '*' ) {
							$( this ).show();
							$( this ).addClass( 'animate' );
						} else {
							if ( ! $( this ).hasClass( filterValue ) ) {
								$( this ).hide();
							} else {
								$( this ).show();
								$( this ).addClass( 'animate' );
							}
						}
					} );
					var $slider = $el.children( '.bb-swiper' )
										.children( '.swiper-container' )[ 0 ].swiper;
					$slider.update();
					$slider.slideTo( 0 );
				} else {
					$grid.children( '.bb-post-item' ).hide().removeClass( 'animate' );

					var $filterItems;

					if ( filterValue == '*' ) {
						$filterItems = $grid.children( '.bb-post-item' );
					} else {
						$filterItems = $grid.children( filterValue );
					}

					$filterItems.show();

					$filterItems.each( function( i, o ) {
						var self = $( this );

						setTimeout( function() {
							self.addClass( 'animate' );
						}, i * 200 );
					} );
				}

				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );
			}
		} );
	}
	
	function betterInitGridAnimation( $grid, $items ) {
		if ( ! $grid.hasClass( 'has-animation' ) ) {
			return;
		}

		var itemQueue  = [],
			queueDelay = animateQueueDelay,
			queueTimer;

		$items.waypoint( function() {
			// Fix for different ver of waypoints plugin.
			var _self = this.element ? this.element : $( this );

			itemQueue.push( _self );
			processItemQueue( itemQueue, queueDelay, queueTimer );
			queueDelay += 250;

			queueResetDelay = setTimeout( function() {
				queueDelay = animateQueueDelay;
			}, animateQueueDelay );
		}, {
			offset: '90%',
			triggerOnce: true
		} );
	}
	
});